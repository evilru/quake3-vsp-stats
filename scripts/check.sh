#!/usr/bin/env bash
# Deterministischer Prüflauf: Tests unter 7.4 und 8.5, dann CLI-Imports unter 8.5.
# Aufruf: scripts/check.sh [7.4|8.5]   (ohne Argument: beide Versionen)
# Exit-Code 0 = alles sauber. Ausgabe: erste Fundstelle je Schritt.
cd "$(dirname "$0")/.."
versions=("${@:-7.4 8.5}"); read -ra versions <<< "${versions[*]}"
rc=0
vsp-db-start >/dev/null 2>&1 </dev/null || { echo "DB-Start fehlgeschlagen"; exit 2; }
[ -x vendor/bin/phpunit ] || { echo "vendor/ fehlt: scripts/bootstrap.sh ausführen"; exit 2; }
for v in "${versions[@]}"; do
  php-use "$v" >/dev/null
  echo "== PHPUnit unter $(php -r 'echo PHP_VERSION;')"
  vendor/bin/phpunit --no-coverage 2>&1 | tail -25 || rc=1
  [ "${PIPESTATUS[0]}" -eq 0 ] || rc=1
done
if [[ " ${versions[*]} " == *" 8.5 "* ]]; then
  php-use 8.5 >/dev/null
  for pair in "q3a baseq3.log" "q3a-xp xp.log" "q3a-osp osp.log"; do
    set -- $pair
    echo "== Import $1 ($2) unter PHP $(php -r 'echo PHP_VERSION;')"
    out=$(php vsp.php -c pub/configs/cfg-default.php -l "$1" -p savestate 1 "$2" 2>&1 </dev/null)
    echo "$out" | grep -nE 'Warning|Deprecated|Fatal|Error|Exception' | head -5 && rc=1
  done
fi
exit $rc
