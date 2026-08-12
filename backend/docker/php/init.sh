#!/bin/bash
set -e

cd /var/www

echo "Waiting for MySQL to be ready..."
/usr/local/bin/wait-for-it.sh mysql:3306 --timeout=90 --strict -- echo "MySQL is up!"

echo "Ensuring database exists..."
mysql --skip-ssl -h mysql -uroot -p"${MYSQL_ROOT_PASSWORD}" -e "
    CREATE DATABASE IF NOT EXISTS \`${DB_DATABASE:-afim}\`;
" || echo "Warning: could not ensure database exists; continuing"

echo "Syncing frontend build into shared public-build volume..."
mkdir -p public/build
cp -a /opt/app-build/. public/build/

echo "Running database migrations..."
php artisan migrate --force

if [ "${RUN_SEEDERS:-false}" = "true" ]; then
    echo "Running database seeders..."
    php artisan db:seed --force
fi

echo "Linking storage..."
php artisan storage:link || true

echo "Caching config / views..."
php artisan config:cache
php artisan view:cache || echo "view:cache failed; continuing — views will compile on demand"

echo "Fixing permissions..."
mkdir -p public/uploads
chown -R www-data:www-data storage bootstrap/cache public/uploads
chmod -R 775 storage bootstrap/cache public/uploads

echo "Initialization complete. Starting supervisord..."
exec /usr/bin/supervisord -c /etc/supervisor/supervisord.conf
