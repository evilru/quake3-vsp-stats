#!/usr/bin/env bash
# Prepare the workspace: start DB, install vendor/ from the seeded composer cache (PHP 7.4).
cd "$(dirname "$0")/.."
export COMPOSER_ALLOW_SUPERUSER=1
scripts/vsp-db-start </dev/null
if [ ! -f vendor/autoload.php ]; then
  scripts/php-use 7.4 >/dev/null
  php scripts/composer-seed.php composer.lock
  composer install --no-interaction --no-progress </dev/null
fi
