#!/usr/bin/env bash
# Bereitet die Arbeitsumgebung vor: DB starten, vendor/ installieren (PHP 7.4).
cd "$(dirname "$0")/.."
vsp-db-start </dev/null
if [ ! -x vendor/bin/phpunit ]; then
  php-use 7.4 >/dev/null
  composer install --no-interaction --prefer-dist </dev/null
fi
