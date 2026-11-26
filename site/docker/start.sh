#!/bin/sh
# Starts the review copy. Render's free disk is wiped on every restart,
# so the database is rebuilt with the real content plus sample data each time.
set -e
cd /var/www/html

# A fresh encryption key per start is fine here: nothing is kept between restarts anyway.
if [ -z "$APP_KEY" ]; then
    APP_KEY="base64:$(head -c 32 /dev/urandom | base64)"
    export APP_KEY
fi

mkdir -p storage/logs storage/app/public storage/framework/views storage/framework/sessions storage/framework/cache/data bootstrap/cache
touch database/database.sqlite
php artisan migrate:fresh --seed --force --no-interaction

# The admin account comes from ADMIN_EMAIL and ADMIN_PASSWORD, set privately in the Render dashboard.
if [ -n "$ADMIN_EMAIL" ] && [ -n "$ADMIN_PASSWORD" ]; then
    php artisan kasawo:admin "$ADMIN_EMAIL" --name="${ADMIN_NAME:-Admin}" --no-interaction
else
    echo "ADMIN_EMAIL / ADMIN_PASSWORD not set: no admin account was created."
fi

php artisan storage:link --force --no-interaction >/dev/null 2>&1 || true
php artisan optimize --no-interaction
chown -R www-data:www-data storage bootstrap/cache database

# Listen on the port Render gives us.
sed -ri "s/^Listen .*/Listen ${PORT:-10000}/" /etc/apache2/ports.conf
sed -ri "s/<VirtualHost \*:[0-9]+>/<VirtualHost *:${PORT:-10000}>/" /etc/apache2/sites-available/000-default.conf

exec apache2-foreground
