#!/bin/bash
set -e

echo "==> Generando APP_KEY si no existe..."
php artisan key:generate --force 2>/dev/null || true

echo "==> Migraciones..."
php artisan migrate --force

echo "==> Seeders..."
php artisan db:seed --class=DepartamentosSeeder --force
php artisan db:seed --class=CargosSeeder --force
php artisan db:seed --class=MunicipiosSeeder --force
php artisan db:seed --class=LocalidadesSeeder --force
php artisan db:seed --class=AdminUserSeeder --force

echo "==> Optimizando..."
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan storage:link || true

echo "==> Iniciando Apache..."
apache2-foreground
