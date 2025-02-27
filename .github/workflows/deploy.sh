#!/bin/sh
# Change to the project directory
cd /home/domgascertificat/gas_certificate_app

# Pull the latest changes from the git repository
git pull origin main

# Install/update composer dependencies
composer install --no-interaction

export PATH=/opt/cpanel/ea-nodejs16/bin/:$PATH
# Install NPM dependencies and build assets
npm install
npm run build
rm -rf node_modules

# Run database migrations
php artisan migrate --force

# Run Seeder workers
php artisan db:seed

# Clear caches
php artisan cache:clear

# Clear and cache routes
php artisan route:cache

# Clear and cache config
php artisan config:cache

# Clear and cache views
php artisan view:cache

#Clear all
php artisan optimize:clear