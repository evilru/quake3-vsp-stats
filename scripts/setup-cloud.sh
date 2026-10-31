#!/usr/bin/env bash
# Cloud environment setup (Ubuntu 24.04, root, full network access).
# Installs PHP 7.4 + 8.5 (ondrej/php PPA), MariaDB, Composer, seeds the Composer cache, starts the DB.
set -euo pipefail
export DEBIAN_FRONTEND=noninteractive
export COMPOSER_ALLOW_SUPERUSER=1 COMPOSER_NO_INTERACTION=1 COMPOSER_PROCESS_TIMEOUT=0

apt-get update -qq
apt-get install -y -qq curl ca-certificates git unzip

# Add ondrej/php PPA manually (add-apt-repository fails here: no apt_pkg)
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
  # Show all errors so 7.4/8.5 differences surface
  printf 'error_reporting=E_ALL\ndisplay_errors=1\n' > "/etc/php/$v/cli/conf.d/99-dev.ini"
done

apt-get install -y -qq mariadb-server mariadb-client

# Tools (php-use, vsp-db-start) and composer seeding come from a shallow clone: setup has no repo context.
REPO=$(mktemp -d)
git clone -q --depth 1 --branch feature/php8 https://github.com/evilru/quake3-vsp-stats.git "$REPO" \
  || git clone -q --depth 1 https://github.com/evilru/quake3-vsp-stats.git "$REPO"
install -m 0755 "$REPO/scripts/php-use" "$REPO/scripts/vsp-db-start" /usr/local/bin/

php-use 7.4

# Install Composer if missing
if ! command -v composer >/dev/null; then
  curl -fsSL https://getcomposer.org/installer -o /tmp/composer-setup.php
  php /tmp/composer-setup.php --install-dir=/usr/local/bin --filename=composer
  rm -f /tmp/composer-setup.php
fi

# Seed the Composer cache (GitHub zipball/codeload return 403; Composer would clone full mirrors).
php "$REPO/scripts/composer-seed.php" "$REPO/composer.lock" || echo "WARN: composer-seed failed"
rm -rf "$REPO"

# Start the DB now
vsp-db-start </dev/null || echo "WARN: DB start failed"

echo "Done. php-use 7.4|8.5, vsp-db-start, scripts/check.sh"
