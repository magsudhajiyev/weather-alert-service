#!/bin/bash

# Wait for MySQL to be fully ready
echo "Waiting for MySQL to be ready..."
while ! mysqladmin ping -h mysql --silent; do
    sleep 1
done

# Navigate to the Laravel project directory
cd /var/www/html

# Generate the application key
if [ ! -f .env ]; then
    echo ".env file is missing.."
    echo ".env file is beeing copied from .env.example. Go ahead and put the database credentials."
    cp .env.example .env
fi

echo "Generating application key..."
php artisan key:generate

# Check if node_modules directory exists, if not, install dependencies
if [ ! -d "node_modules" ]; then
    echo "Installing npm dependencies..."
    ./vendor/bin/sail npm install
fi

# Run npm build process to generate Vite assets
if [ ! -f "public/build/manifest.json" ]; then
    echo "Building Vite assets..."
    ./vendor/bin/sail npm run build
fi

# Clear caches
echo "Clearing Laravel caches..."
php artisan config:clear
php artisan cache:clear

# Run database migrations and seeders
echo "Running database migrations and seeders..."
php artisan migrate --force
php artisan db:seed --force

# Start the original entrypoint (Apache/PHP-FPM)
exec /usr/local/bin/start-container
