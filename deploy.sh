#!/bin/bash

# Run migrations
php artisan migrate --force

# Restore default application data so login accounts exist on fresh deploys
php artisan db:seed --force

# Optional: Cache configuration for speed
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Start the PHP-FPM process and Nginx
php-fpm -D && nginx -g 'daemon off;'
