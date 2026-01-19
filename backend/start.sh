#!/bin/bash
set -e

# Clear caches
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear

# Create storage symlink
php artisan storage:link

# Start queue worker in background
php artisan queue:work --tries=3 --timeout=90 &

# Start Laravel server
php artisan serve --host=0.0.0.0 --port=$PORT
