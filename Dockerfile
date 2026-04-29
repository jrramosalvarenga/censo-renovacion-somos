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

# Credenciales Neon y configuración de producción
ENV DATABASE_URL="postgresql://neondb_owner:npg_tkTFA2DbRLn5@ep-curly-poetry-ano8hn7w-pooler.c-6.us-east-1.aws.neon.tech/RenovacionSomos?sslmode=require"
ENV APP_NAME="Censo Renovacion Somos"
ENV APP_ENV="production"
ENV APP_DEBUG="false"
ENV SESSION_DRIVER="file"
ENV CACHE_STORE="file"
ENV QUEUE_CONNECTION="sync"
ENV LOG_LEVEL="error"
ENV ULTRAMSG_INSTANCE="instance172465"
ENV ULTRAMSG_TOKEN="gg1udf6cwn10vbc1"

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

// Construir .env
$content = 'APP_NAME="Censo Renovacion Somos"' . "\n"
    . 'APP_ENV=production' . "\n"
    . 'APP_KEY=' . "\n"
    . 'APP_DEBUG=false' . "\n"
    . 'DB_CONNECTION=pgsql' . "\n"
    . 'DATABASE_URL=' . $DATABASE_URL . "\n"
    . 'SESSION_DRIVER=file' . "\n"
    . 'CACHE_STORE=file' . "\n"
    . 'QUEUE_CONNECTION=sync' . "\n"
    . 'LOG_LEVEL=error' . "\n"
    . 'ULTRAMSG_INSTANCE=' . (getenv('ULTRAMSG_INSTANCE') ?: 'instance172465') . "\n"
    . 'ULTRAMSG_TOKEN=' . (getenv('ULTRAMSG_TOKEN') ?: 'gg1udf6cwn10vbc1') . "\n";
file_put_contents('/var/www/html/.env', $content);
echo "==> .env creado\n";

$cmds = [
    'php artisan key:generate --force',
    'php artisan config:clear',
    'php artisan migrate --force',
    'php artisan db:seed --class=DepartamentosSeeder --force',
    'php artisan db:seed --class=CargosSeeder --force',
    'php artisan db:seed --class=MunicipiosSeeder --force',
    'php artisan db:seed --class=LocalidadesSeeder --force',
    'php artisan db:seed --class=AdminUserSeeder --force',
    'php artisan config:cache',
    'php artisan route:cache',
    'php artisan view:cache',
    'php artisan storage:link',
    'apache2-foreground',
];

$skip = ['php artisan storage:link', 'php artisan key:generate --force'];

foreach ($cmds as $cmd) {
    echo "==> $cmd\n";
    passthru($cmd, $code);
    if ($code !== 0 && !in_array($cmd, $skip)) {
        echo "==> ERROR: $cmd (codigo $code)\n";
        exit($code);
    }
}
PHPEOF

EXPOSE 80

CMD ["php", "/startup.php"]
