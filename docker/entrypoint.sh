#!/bin/bash

# Wait for MySQL to be fully ready
echo "Waiting for MySQL to be ready..."
while ! mysqladmin ping -h mysql --silent; do
    sleep 1
done

# Navigate to the Laravel project directory
cd /var/www/html

# Run database migrations
echo "Running database migrations & seeders..."
php artisan migrate 
php artisan db:seed

# Start the original entrypoint (Apache/PHP-FPM)
exec /usr/local/bin/start-container
