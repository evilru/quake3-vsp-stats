#!/bin/sh
set -e

if [ "$1" = "test" ]; then
    echo "Checking composer dependencies..."

    # Check if vendor directory exists
    if [ ! -d "vendor" ]; then
        echo "No vendor directory found, running composer install..."
        composer install
    else
        # Check if composer.json was modified after composer.lock
        if [ "composer.json" -nt "composer.lock" ]; then
            echo "composer.json has changed, updating dependencies..."
            composer update
        else
            echo "Dependencies are up to date"
        fi
    fi

    echo "Running tests..."
    exec ./vendor/bin/phpunit
fi

exec "$@"
