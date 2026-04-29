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

# Crear script de arranque como archivo PHP (sin problemas de CRLF ni escapado)
RUN cat > /startup.php << 'PHPEOF'
<?php
// Construir .env limpio desde variables de entorno de Render
$keys = [
    'APP_NAME', 'APP_ENV', 'APP_KEY', 'APP_DEBUG', 'APP_URL',
    'DATABASE_URL', 'SESSION_DRIVER', 'CACHE_STORE', 'QUEUE_CONNECTION',
    'LOG_LEVEL', 'GOOGLE_CLIENT_ID', 'GOOGLE_CLIENT_SECRET',
];
$lines = ['DB_CONNECTION=pgsql'];
foreach ($keys as $k) {
    $v = getenv($k);
    if ($v !== false && $v !== '') {
        $lines[] = $k . '=' . $v;
    }
}
file_put_contents('/var/www/html/.env', implode("\n", $lines) . "\n");
echo "==> .env creado con " . count($lines) . " variables\n";

chdir('/var/www/html');

$cmds = [
    'php artisan key:generate --force',
    'php artisan config:clear',
    'sleep 3',
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

foreach ($cmds as $cmd) {
    echo "==> $cmd\n";
    passthru($cmd, $code);
    if ($code !== 0 && $cmd !== 'php artisan storage:link') {
        exit($code);
    }
}
PHPEOF

EXPOSE 80

CMD ["php", "/startup.php"]
