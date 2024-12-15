#!/bin/bash

cd /var/www/html

# Clear caches first
php artisan config:clear
php artisan cache:clear

echo "Starting scheduler at $(date)"

while true; do
    echo "Running scheduler at $(date)"
    # Run with maximum verbosity
    php artisan schedule:run -vvv
    sleep 60
done