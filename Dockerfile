FROM php:8.3-apache

# Extensiones
RUN apt-get update && apt-get install -y \
    libpq-dev libzip-dev libpng-dev libjpeg-dev libfreetype6-dev \
    zip unzip git curl \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo pdo_pgsql pgsql zip gd \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

COPY composer.json composer.lock ./
RUN composer install --no-dev --optimize-autoloader --no-scripts

COPY . .

RUN composer run-script post-autoload-dump 2>/dev/null || true

# Apache apunta al public/
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' \
    /etc/apache2/sites-available/*.conf \
    /etc/apache2/apache2.conf \
    /etc/apache2/conf-available/*.conf \
    && a2enmod rewrite headers

# Permisos
RUN chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

# Configuración de producción
ENV DATABASE_URL="postgresql://neondb_owner:npg_tkTFA2DbRLn5@ep-curly-poetry-ano8hn7w.c-6.us-east-1.aws.neon.tech/RenovacionSomos?sslmode=require"
ENV ULTRAMSG_INSTANCE="instance172465"
ENV ULTRAMSG_TOKEN="gg1udf6cwn10vbc1"

# Generar APP_KEY en tiempo de build y escribir .env completo y válido
RUN php artisan key:generate --show > /tmp/app_key.txt 2>&1; \
    APP_KEY=$(cat /tmp/app_key.txt | tr -d '[:space:]'); \
    DB_URL="postgresql://neondb_owner:npg_tkTFA2DbRLn5@ep-curly-poetry-ano8hn7w.c-6.us-east-1.aws.neon.tech/RenovacionSomos?sslmode=require"; \
    printf 'APP_NAME="Censo Renovacion Somos"\nAPP_ENV=production\nAPP_KEY=%s\nAPP_DEBUG=false\nDB_CONNECTION=pgsql\nDATABASE_URL=%s\nSESSION_DRIVER=file\nCACHE_STORE=file\nQUEUE_CONNECTION=sync\nLOG_LEVEL=error\nULTRASMG_INSTANCE=instance172465\nULTRASMG_TOKEN=gg1udf6cwn10vbc1\n' "$APP_KEY" "$DB_URL" > /var/www/html/.env; \
    cat /var/www/html/.env

# Script de arranque
RUN cat > /startup.php << 'PHPEOF'
<?php
chdir('/var/www/html');

$DATABASE_URL = getenv('DATABASE_URL') ?: '';
echo "==> DATABASE_URL: " . substr($DATABASE_URL, 0, 60) . "...\n";

if (empty($DATABASE_URL)) {
    echo "[ERROR] DATABASE_URL vacia.\n";
    exit(1);
}

echo "==> .env ya existe desde build\n";

// Configurar Apache en el puerto que pide Render (PORT env var)
$port = getenv('PORT') ?: '10000';
echo "==> Configurando Apache en puerto $port\n";
exec("sed -i 's/Listen 80/Listen $port/' /etc/apache2/ports.conf");
exec("sed -i 's/<VirtualHost \\*:80>/<VirtualHost *:$port>/' /etc/apache2/sites-available/000-default.conf");
exec("sed -i 's/<VirtualHost \\*:80>/<VirtualHost *:$port>/' /etc/apache2/sites-enabled/000-default.conf 2>/dev/null || true");

// Arrancar Apache en segundo plano PRIMERO para que Render vea el puerto
echo "==> Iniciando Apache en segundo plano...\n";
exec('apache2-foreground > /var/log/apache2/startup.log 2>&1 &');
sleep(3);
echo "==> Apache iniciado\n";

// Ejecutar setup de base de datos
$cmds = [
    'php artisan config:clear',
    'php artisan migrate:fresh --force',
    'php artisan db:seed --class=DepartamentosSeeder --force',
    'php artisan db:seed --class=CargosSeeder --force',
    'php artisan db:seed --class=MunicipiosSeeder --force',
    'php artisan db:seed --class=LocalidadesSeeder --force',
    'php artisan db:seed --class=AdminUserSeeder --force',
    'php artisan config:cache',
    'php artisan route:cache',
    'php artisan view:cache',
    'php artisan storage:link',
];

$skip = ['php artisan storage:link'];

foreach ($cmds as $cmd) {
    echo "==> $cmd\n";
    passthru($cmd, $code);
    if ($code !== 0 && !in_array($cmd, $skip)) {
        echo "==> ERROR: $cmd (codigo $code)\n";
        // No salir — Apache ya está corriendo
    }
}

echo "==> Setup completado. Manteniendo proceso activo...\n";
// Mantener el proceso vivo mientras Apache corre en segundo plano
passthru('wait');
PHPEOF

EXPOSE 80

CMD ["php", "/startup.php"]
