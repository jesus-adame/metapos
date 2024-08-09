#!/bin/bash
git pull origin main
composer install -q --no-ansi --no-interaction --no-scripts --no-progress --prefer-dist
php artisan key:generate
chmod -R 777 storage bootstrap/cache
npm install
npm run build
# Copiar los archivos build a la ubicación correcta en el servidor
