#!/usr/bin/env bash
# Setup-Skript für die Claude-Code-Cloud-Umgebung (Ubuntu 24.04, root, voller Netzwerkzugriff).
# Installiert PHP 7.4 und PHP 8.5 (ondrej/php PPA) nebeneinander, MariaDB, Composer,
# wärmt den Composer-Cache vor und startet die DB, damit beim Arbeiten alles bereit ist.
set -euo pipefail
export DEBIAN_FRONTEND=noninteractive
export COMPOSER_ALLOW_SUPERUSER=1 COMPOSER_NO_INTERACTION=1 COMPOSER_PROCESS_TIMEOUT=0

apt-get update -qq
apt-get install -y -qq curl ca-certificates git unzip

# ondrej/php PPA manuell einrichten (add-apt-repository braucht apt_pkg und scheitert hier)
install -d -m 0755 /etc/apt/keyrings
curl -fsSL "https://keyserver.ubuntu.com/pks/lookup?op=get&search=0xB8DC7E53946656EFBCE4C1DD71DAEAAB4AD4CAB6" \
  -o /etc/apt/keyrings/ondrej-php.asc
cat > /etc/apt/sources.list.d/ondrej-php.sources <<SRC
Types: deb
URIs: https://ppa.launchpadcontent.net/ondrej/php/ubuntu
Suites: $(. /etc/os-release && echo "$VERSION_CODENAME")
Components: main
Signed-By: /etc/apt/keyrings/ondrej-php.asc
SRC
apt-get update -qq

for v in 7.4 8.5; do
  apt-get install -y -qq "php$v-cli" "php$v-mysql" "php$v-mbstring" "php$v-xml" "php$v-gd" "php$v-curl" "php$v-zip"
  # Alle Fehler sichtbar machen, damit Unterschiede zwischen 7.4 und 8.5 auffallen
  printf 'error_reporting=E_ALL\ndisplay_errors=1\n' > "/etc/php/$v/cli/conf.d/99-dev.ini"
done

apt-get install -y -qq mariadb-server mariadb-client

# php-use 7.4 | 8.5  -> schaltet den Befehl `php` auf die gewünschte Version um
cat > /usr/local/bin/php-use <<'USE'
#!/usr/bin/env bash
set -e
case "${1:-}" in
  7.4|8.5) ln -sf "/usr/bin/php$1" /usr/local/bin/php ;;
  *) echo "Usage: php-use 7.4|8.5" >&2; exit 1 ;;
esac
php -v | head -1
USE

# vsp-db-start -> startet MariaDB (voll abgekoppelt, blockiert keine Pipes) und legt die DB an
cat > /usr/local/bin/vsp-db-start <<'DB'
#!/usr/bin/env bash
set -e
mkdir -p /run/mysqld && chown mysql:mysql /run/mysqld
if ! mysqladmin ping --silent 2>/dev/null; then
  setsid nohup mysqld_safe >/var/log/mariadb-start.log 2>&1 </dev/null &
  for _ in $(seq 30); do mysqladmin ping --silent 2>/dev/null && break; sleep 1; done
fi
mysql -e "ALTER USER 'root'@'localhost' IDENTIFIED VIA unix_socket OR mysql_native_password USING PASSWORD('secretPassword'); CREATE DATABASE IF NOT EXISTS vsp; FLUSH PRIVILEGES;"
echo "MariaDB läuft, DB 'vsp' bereit (root / secretPassword, wie in cfg-default.php)"
DB
chmod +x /usr/local/bin/php-use /usr/local/bin/vsp-db-start

php-use 7.4

# Composer installieren, falls nicht vorhanden
if ! command -v composer >/dev/null; then
  curl -fsSL https://getcomposer.org/installer -o /tmp/composer-setup.php
  php /tmp/composer-setup.php --install-dir=/usr/local/bin --filename=composer
  rm -f /tmp/composer-setup.php
fi

# Composer-Cache vorwärmen. GitHub-Dist-Downloads scheitern hier ("Could not authenticate"),
# Composer fällt dann auf `git clone` zurück (>15 min). Das passiert einmalig hier im Setup;
# danach ist `composer install` im Repo nur noch ein Cache-Treffer.
REPO=/home/user/quake3-vsp-stats
if [ -f "$REPO/composer.lock" ]; then
  (cd "$REPO" && composer install --no-interaction --no-progress) || echo "WARN: composer install im Repo fehlgeschlagen"
else
  W=$(mktemp -d)
  (cd "$W" && composer require --dev --no-interaction --no-progress --prefer-source \
     "phpunit/phpunit:^9.0" "squizlabs/php_codesniffer:^3.0" "phpstan/phpstan:^1.0" "phpmd/phpmd:^2.13") \
    || echo "WARN: Cache-Vorwärmen fehlgeschlagen"
  rm -rf "$W"
fi

# DB schon jetzt starten
vsp-db-start </dev/null || echo "WARN: DB-Start fehlgeschlagen"

echo "Fertig. Wechsel mit: php-use 7.4 | php-use 8.5 - DB: vsp-db-start - Prüfen: scripts/check.sh"
