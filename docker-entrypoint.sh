#!/bin/sh
set -e

# Wait until DB is ready
echo "⏳ Waiting for database..."
until php bin/console doctrine:query:sql "SELECT 1" > /dev/null 2>&1; do
  sleep 1
done

echo "✅ Database is ready. Running migrations..."
php bin/console doctrine:migrations:migrate --no-interaction

# Symfony setup
php bin/console cache:clear --env=prod
php bin/console cache:warmup --env=prod
php bin/console assets:install public
php bin/console tailwind:init || true
php bin/console tailwind:build || true
php bin/console asset-map:compile || true
php bin/console cache:clear --env=prod

# Fix permissions (optional)
#chown -R www-data:www-data var public/uploads

# Start PHP-FPM
exec php-fpm