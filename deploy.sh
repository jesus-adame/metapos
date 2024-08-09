#!/bin/bash
git pull origin main
composer install -q --no-ansi --no-interaction --no-scripts --no-progress --prefer-dist
chmod -R 777 storage bootstrap/cache
php artisan optimize:clear
php artisan optimize
php artisan key:generate
php artisan migrate
php artisan tenants:migrate
npm install
npm run build
# Copiar los archivos build a la ubicación correcta en el servidor
