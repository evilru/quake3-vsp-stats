#!/usr/bin/env bash
# Build the image with podman and run the compose stack (podman-compose) end to end:
# import runs, web page has no PHP messages, container is unprivileged/read-only, DB has no published port,
# data survives a down/up. Exit 0 = all checks passed.
# Usage: scripts/compose-test.sh [xp.log]      (needs: podman, podman-compose, network access to docker.io)
set -u
cd "$(dirname "$0")/.."
LOG="${1:-xp.log}"
T=$(mktemp -d); P=vspcheck; fail=0
ok()  { echo "ok   $*"; }
bad() { echo "FAIL $*"; fail=1; }
cleanup() { (cd "$T" && podman-compose -p $P down -v >/dev/null 2>&1); podman rm -af >/dev/null 2>&1; podman network prune -f >/dev/null 2>&1; rm -rf "$T"; }
trap cleanup EXIT

podman build --isolation=chroot --network=host -t localhost/vsp-test . >"$T/build.log" 2>&1 || { tail -20 "$T/build.log"; exit 2; }
cp docker-compose.yml "$T/"; cp "$LOG" "$T/games.log"
cat > "$T/override.yml" <<'YML'
services:
  web:
    image: localhost/vsp-test
    pull_policy: never
    ports:
      - "8088:8080"
    volumes:
      - ./games.log:/vsp/games.log:ro
      - logdata:/vsp/logdata
    environment:
      LOGTYPE: q3a-xp
      VSP_WEB_PASSWORD: secret123
      IMPORT_INTERVAL: "20"
YML
cat > "$T/.env" <<'ENV'
MARIADB_DATABASE=vsp
MARIADB_USER=vspuser
MARIADB_PASSWORD=pa$$word"with`special
DB_NAME=vsp
DB_USERNAME=vspuser
DB_PASSWORD=pa$$word"with`special
ENV
up() { (cd "$T" && podman-compose -p $P -f docker-compose.yml -f override.yml up -d >"$T/up.log" 2>&1); }
wait_import() { timeout 180 bash -c "until podman logs ${P}_web_1 2>&1 | grep -aq 'processed in'; do sleep 3; done"; }

up; wait_import && ok "import finished" || bad "import did not finish"
[ "$(podman exec ${P}_web_1 id -un)" = www-data ] && ok "runs as www-data" || bad "not www-data"
podman exec ${P}_web_1 touch /vsp/x 2>/dev/null && bad "root file system writable" || ok "root file system read-only"
[ "$(podman exec ${P}_web_1 grep CapEff /proc/1/status | awk '{print $2}')" = 0000000000000000 ] && ok "no capabilities" || bad "capabilities left"
[ -z "$(podman port ${P}_db_1)" ] && ok "database publishes no port" || bad "database port published"
body=$(curl -s http://127.0.0.1:8088/pub/themes/bismarck/index.php)
[ -n "$body" ] && ok "web page served" || bad "no web page"
echo "$body" | sed 's/<[^>]*>//g' | grep -aq "Warning\|Deprecated\|Fatal" && bad "PHP messages on the web page" || ok "no PHP messages on the web page"
podman exec ${P}_web_1 curl -fsS -o /dev/null http://127.0.0.1:8080/ && ok "healthcheck command succeeds" || bad "healthcheck command fails"
pw=$(podman exec ${P}_web_1 printenv DB_PASSWORD)
games() { podman exec ${P}_db_1 mariadb -uvspuser -p"$pw" vsp -N -e "select count(*) from vsp_gameprofile" 2>/dev/null; }
before=$(games); [ "${before:-0}" -gt 0 ] && ok "games imported ($before)" || bad "no games imported"
(cd "$T" && podman-compose -p $P down >/dev/null 2>&1); up; wait_import
[ "$(games)" = "$before" ] && ok "data survives down/up" || bad "data lost after down/up"
exit $fail
