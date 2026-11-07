#!/usr/bin/env bash
# Prepare the workspace: start DB, install vendor/ from the seeded composer cache (PHP 8.5).
cd "$(dirname "$0")/.." || exit 1
export COMPOSER_ALLOW_SUPERUSER=1
scripts/vsp-db-start </dev/null
if [ ! -f vendor/autoload.php ]; then
  scripts/php-use 8.5 >/dev/null
  php scripts/composer-seed.php composer.lock
  composer install --no-interaction --no-progress </dev/null
fi
