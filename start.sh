#!/bin/bash
set -e

echo "==> Ejecutando migraciones..."
php artisan migrate --force

echo "==> Ejecutando seeders iniciales..."
php artisan db:seed --class=DepartamentosSeeder --force
php artisan db:seed --class=CargosSeeder --force
php artisan db:seed --class=MunicipiosSeeder --force
php artisan db:seed --class=LocalidadesSeeder --force

echo "==> Enlazando storage..."
php artisan storage:link || true

echo "==> Iniciando servidor..."
php artisan serve --host=0.0.0.0 --port=${PORT:-8000}
