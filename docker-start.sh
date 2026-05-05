#!/bin/bash
set -e

# ── 1. Construir .env: vars de entorno reales toman prioridad ──────────
# Las vars del hosting van PRIMERO; .env.example solo rellena lo que falta
printenv | grep -E "^(APP_|DB_|DATABASE_URL|SESSION_|CACHE_|QUEUE_|GOOGLE_|LOG_|CLOUDINARY_|MAIL_)" > /tmp/.env.runtime

if [ -f .env.example ]; then
    while IFS= read -r line; do
        # Ignorar comentarios y líneas vacías
        [[ "$line" =~ ^#.*$ || -z "$line" ]] && continue
        key="${line%%=*}"
        # Solo añadir si la var no está ya definida por el entorno real
        if [ -n "$key" ] && ! grep -q "^${key}=" /tmp/.env.runtime; then
            echo "$line" >> /tmp/.env.runtime
        fi
    done < .env.example
fi

cp /tmp/.env.runtime .env

# ── 2. Generar APP_KEY solo si no viene del entorno ────────────────────
if [ -z "$APP_KEY" ] && ! grep -q "^APP_KEY=base64:" .env; then
    echo "==> Generando APP_KEY (primera vez)..."
    php artisan key:generate --force
else
    echo "==> APP_KEY ya configurado, omitiendo key:generate."
fi

echo "==> Limpiando cache de config..."
php artisan config:clear

echo "==> Esperando base de datos..."
sleep 3

echo "==> Migraciones (sin borrar datos)..."
php artisan migrate --force

echo "==> Seeders iniciales (firstOrCreate, no borran datos)..."
php artisan db:seed --class=DepartamentosSeeder --force
php artisan db:seed --class=CargosSeeder        --force
php artisan db:seed --class=MunicipiosSeeder     --force
php artisan db:seed --class=LocalidadesSeeder    --force
php artisan db:seed --class=AdminUserSeeder      --force

echo "==> Optimizando..."
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan storage:link || true

echo "==> Iniciando Apache..."
apache2-foreground
