#!/bin/sh
set -e

# Wait until DB is ready
echo "⏳ Waiting for database..."
until php bin/console doctrine:query:sql "SELECT 1" > /dev/null 2>&1; do
  sleep 1
done

echo "✅ Database is ready. Running migrations..."
php bin/console doctrine:migrations:migrate --no-interaction

# Start PHP-FPM
exec php-fpm