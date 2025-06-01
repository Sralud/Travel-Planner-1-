#!/usr/bin/env bash

# Exit on error
set -o errexit

# Install PHP dependencies
composer install --no-dev --prefer-dist

# Clear and cache configurations
php artisan config:clear
php artisan config:cache
php artisan route:clear
php artisan route:cache
php artisan view:clear
php artisan view:cache

# Install Node.js dependencies
npm install

# Build frontend assets
npm run build

# Run database migrations
php artisan migrate --force