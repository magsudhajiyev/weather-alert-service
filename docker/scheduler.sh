#!/bin/bash

cd /var/www/html

# Wait for MySQL to be fully ready
echo "Waiting for MySQL to be ready..."
while ! mysqladmin ping -h mysql -u${DB_USERNAME:-sail} -p${DB_PASSWORD:-password} --silent; do
    sleep 1
done

# Clear caches
echo "Clearing Laravel caches..."
php artisan config:clear
php artisan cache:clear


echo "Starting scheduler at $(date)"

# Run the scheduler loop
while true; do
    echo "Running scheduler at $(date)"
    php artisan schedule:run -vvv
    sleep 60
done
