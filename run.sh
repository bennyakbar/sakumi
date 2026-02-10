#!/bin/sh
cd /var/www/html
php artisan migrate --force
# Only seed if needed, or check if seed already ran to avoid duplicates? 
# For now, simplistic approach. AdminTUSeeder uses firstOrCreate so it's safe.
php artisan db:seed --class=AdminTUSeeder --force
apache2-foreground
