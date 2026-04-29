#!/bin/bash
set -e

# Crear .env si no existe (en producción las vars vienen del hosting)
if [ ! -f .env ]; then
    cp .env.example .env
fi

# Inyectar variables de entorno al .env para que artisan las lea
printenv | grep -E "^(APP_|DB_|DATABASE_URL|SESSION_|CACHE_|QUEUE_|GOOGLE_|LOG_)" >> .env 2>/dev/null || true

echo "==> Generando APP_KEY..."
php artisan key:generate --force

echo "==> Limpiando cache de config..."
php artisan config:clear

echo "==> Esperando base de datos..."
sleep 3

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
