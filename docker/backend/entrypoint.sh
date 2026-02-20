#!/bin/bash
set -e

cd /var/www/html

if [ ! -f .env ]; then
    cp .env.example .env
fi

composer install --no-interaction --optimize-autoloader

if ! grep -q "APP_KEY=base64:" .env; then
    php artisan key:generate --force
fi

if ! grep -q "JWT_SECRET=." .env 2>/dev/null; then
    php artisan jwt:secret --force
fi

chmod -R 777 storage bootstrap/cache

echo "Waiting for MySQL..."
until php -r "new PDO('mysql:host=mysql;port=3306;dbname=apostatudo', 'apostatudo', 'secret');" 2>/dev/null; do
    sleep 2
done
echo "MySQL connected!"

php artisan migrate --force
php artisan db:seed --force

php artisan config:cache
php artisan route:cache

echo "Backend ready!"
touch /tmp/backend-ready

exec php-fpm
