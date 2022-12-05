#!/usr/bin/env sh

cp .env.example .env
sed -i -e 's/DB_HOST=127.0.0.1/DB_HOST=mysql/g' .env
sed -i -e 's/DB_PASSWORD=/DB_PASSWORD=secret123/g' .env
sed -i -e 's/DB_DATABASE=laravel/DB_DATABASE=aichat/g' .env
chown -R $USER:www-data storage
chown -R $USER:www-data bootstrap/cache
chmod -R 775 storage
chmod -R 775 bootstrap/cache
sleep 15s
php /app/artisan migrate --seed
php /app/artisan key:generate
/usr/bin/supervisord -n -c /etc/supervisord.conf

exec "$@"
