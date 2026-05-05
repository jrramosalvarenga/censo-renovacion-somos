#!/bin/bash
set -e

# ── 1. Generar APP_KEY solo si no está ya en el entorno ────────────────
if [ -z "$APP_KEY" ]; then
    echo "==> Generando APP_KEY (primera vez)..."
    php artisan key:generate --force
else
    echo "==> APP_KEY ya configurado, omitiendo key:generate."
fi

echo "==> Limpiando cache de config..."
php artisan config:clear

echo "==> Migraciones (sin borrar datos)..."
php artisan migrate --force

echo "==> Seeders iniciales (firstOrCreate, no borran datos)..."
php artisan db:seed --class=DepartamentosSeeder --force
php artisan db:seed --class=CargosSeeder        --force
php artisan db:seed --class=MunicipiosSeeder     --force
php artisan db:seed --class=LocalidadesSeeder    --force
php artisan db:seed --class=AdminUserSeeder      --force

echo "==> Enlazando storage..."
php artisan storage:link || true

echo "==> Iniciando servidor..."
php artisan serve --host=0.0.0.0 --port=${PORT:-8000}
