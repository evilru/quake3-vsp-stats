#!/bin/sh
set -e

if [ "$1" = "test" ]; then
    # Install dependencies if they don't exist
    [ ! -d "vendor" ] && composer install
    exec ./vendor/bin/phpunit
fi

exec "$@"
