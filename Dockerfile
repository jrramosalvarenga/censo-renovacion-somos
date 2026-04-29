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

# Script de arranque
RUN cat > /startup.php << 'PHPEOF'
<?php
chdir('/var/www/html');

echo "==> Variables de entorno detectadas:\n";
$allEnv = getenv();
foreach ($allEnv as $k => $v) {
    if (preg_match('/^(APP_|DB|DATABASE|SESSION|CACHE|QUEUE|LOG_|ULTRA|GOOGLE)/', $k)) {
        echo "    $k=" . (str_contains(strtolower($k), 'password') || str_contains(strtolower($k), 'token') || str_contains(strtolower($k), 'secret') ? '***' : substr($v, 0, 60)) . "\n";
    }
}

$DATABASE_URL = getenv('DATABASE_URL') ?: '';

if (empty($DATABASE_URL)) {
    echo "\n[ERROR] DATABASE_URL no esta configurada en Render.\n";
    echo "Ve a Render > Environment y agrega:\n";
    echo "DATABASE_URL=postgresql://neondb_owner:PASSWORD@HOST/DATABASE?sslmode=require\n\n";
    exit(1);
}

echo "==> DATABASE_URL: " . substr($DATABASE_URL, 0, 50) . "...\n";

// Construir .env limpio
$lines = [
    'APP_NAME="' . (getenv('APP_NAME') ?: 'Censo Renovacion Somos') . '"',
    'APP_ENV='   . (getenv('APP_ENV')  ?: 'production'),
    'APP_KEY='   . (getenv('APP_KEY')  ?: ''),
    'APP_DEBUG=' . (getenv('APP_DEBUG') ?: 'false'),
    'APP_URL='   . (getenv('APP_URL')  ?: 'http://localhost'),
    'DB_CONNECTION=pgsql',
    'DATABASE_URL=' . $DATABASE_URL,
    'SESSION_DRIVER='    . (getenv('SESSION_DRIVER')    ?: 'file'),
    'CACHE_STORE='       . (getenv('CACHE_STORE')       ?: 'file'),
    'QUEUE_CONNECTION='  . (getenv('QUEUE_CONNECTION')  ?: 'sync'),
    'LOG_LEVEL='         . (getenv('LOG_LEVEL')         ?: 'error'),
    'ULTRAMSG_INSTANCE=' . (getenv('ULTRAMSG_INSTANCE') ?: ''),
    'ULTRAMSG_TOKEN='    . (getenv('ULTRAMSG_TOKEN')    ?: ''),
];

file_put_contents('/var/www/html/.env', implode("\n", $lines) . "\n");
echo "==> .env creado correctamente\n";

// Ejecutar comandos
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

$skip_errors = ['php artisan storage:link', 'php artisan key:generate --force'];

foreach ($cmds as $cmd) {
    echo "==> $cmd\n";
    passthru($cmd, $code);
    if ($code !== 0 && !in_array($cmd, $skip_errors)) {
        echo "==> ERROR en: $cmd (código $code)\n";
        exit($code);
    }
}
PHPEOF

EXPOSE 80

CMD ["php", "/startup.php"]
