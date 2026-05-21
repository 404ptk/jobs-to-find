#!/bin/sh
set -e

echo "JobsToFind Docker entrypoint"

if [ ! -f /app/.env ]; then
    cp /app/.env.example /app/.env
fi

# Clean bootstrap cache before any Laravel command to avoid stale provider issues
rm -f /app/bootstrap/cache/*.php

if ! grep -q "^APP_KEY=base64:" /app/.env; then
    php /app/artisan key:generate --force
fi

if [ ! -f /app/database/database.sqlite ]; then
    mkdir -p /app/database
    touch /app/database/database.sqlite
fi

chown -R www-data:www-data /app/storage /app/bootstrap/cache /app/database

php /app/artisan migrate --force
php /app/artisan config:clear
php /app/artisan cache:clear
php /app/artisan view:clear

exec "$@"
