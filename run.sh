#!/bin/sh
cd /var/www/html

# Fix permissions for storage and cache
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Clear caches to ensure new env vars are picked up
php artisan optimize:clear
php artisan config:clear

# Run migrations
php artisan migrate --force

# Seed if needed (AdminTUSeeder checks for existing users so it's safe)
php artisan db:seed --class=AdminTUSeeder --force

# Start Apache
apache2-foreground
