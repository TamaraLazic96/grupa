#!/bin/sh
set -e

# Wait until DB is ready
echo "⏳ Waiting for database..."
until php bin/console doctrine:query:sql "SELECT 1" > /dev/null 2>&1; do
  sleep 1
done

echo "✅ Database is ready. Running migrations..."
php bin/console doctrine:migrations:migrate --no-interaction

## Check if user exists and insert if not
#echo "Checking if the default user exists..."
#USER_EXISTS=$(php bin/console doctrine:query:sql "SELECT 1 FROM user WHERE email = 'igorvukovic86@hotmail.com'" --quiet)
#if [ -z "$USER_EXISTS" ]; then
#  echo "User does not exist, inserting a default user..."
#  php bin/console doctrine:query:sql "
#    INSERT INTO user (email, roles, username, password, first_name, last_name, is_verified, created_at)
#    VALUES ('igorvukovic86@hotmail.com', '[\"ROLE_ADMIN\"]', 'Игор', '\$2y\$13\$ciHV6HAbD1ulTZ6twSsQ2Ox45vHpc72jcOkMxGKSXPf9.LxDpLj9q', 'Игор', 'Вуковић', 1, CURRENT_TIMESTAMP);
#  "
#else
#  echo "User already exists."
#fi

# Start PHP-FPM
exec php-fpm