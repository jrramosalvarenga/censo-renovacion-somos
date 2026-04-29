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

# Script de arranque robusto
RUN cat > /startup.php << 'PHPEOF'
<?php
chdir('/var/www/html');

// Lee una variable de entorno desde todas las fuentes posibles
function env_get(string $key): string {
    $v = getenv($key);
    if ($v !== false) return $v;
    if (isset($_ENV[$key]))    return $_ENV[$key];
    if (isset($_SERVER[$key])) return $_SERVER[$key];
    return '';
}

// Construir .env desde las variables de entorno de Render
$DATABASE_URL = env_get('DATABASE_URL');
$lines = [
    'APP_NAME="' . (env_get('APP_NAME') ?: 'Censo Renovacion Somos') . '"',
    'APP_ENV='   . (env_get('APP_ENV')  ?: 'production'),
    'APP_KEY='   . env_get('APP_KEY'),   // placeholder vacío si no existe → key:generate lo llena
    'APP_DEBUG=' . (env_get('APP_DEBUG') ?: 'false'),
    'APP_URL='   . (env_get('APP_URL')  ?: 'http://localhost'),
    'DB_CONNECTION=pgsql',
    'DATABASE_URL=' . $DATABASE_URL,
    'SESSION_DRIVER='    . (env_get('SESSION_DRIVER')    ?: 'file'),
    'CACHE_STORE='       . (env_get('CACHE_STORE')       ?: 'file'),
    'QUEUE_CONNECTION='  . (env_get('QUEUE_CONNECTION')  ?: 'sync'),
    'LOG_LEVEL='         . (env_get('LOG_LEVEL')         ?: 'error'),
    'ULTRAMSG_INSTANCE=' . env_get('ULTRAMSG_INSTANCE'),
    'ULTRAMSG_TOKEN='    . env_get('ULTRAMSG_TOKEN'),
    'GOOGLE_CLIENT_ID='     . env_get('GOOGLE_CLIENT_ID'),
    'GOOGLE_CLIENT_SECRET=' . env_get('GOOGLE_CLIENT_SECRET'),
];

file_put_contents('/var/www/html/.env', implode("\n", $lines) . "\n");
echo "==> .env creado\n";
echo "==> DATABASE_URL configurada: " . (empty($DATABASE_URL) ? 'NO (revisar variable en Render)' : 'SI (' . substr($DATABASE_URL, 0, 30) . '...)') . "\n";

// Comandos de arranque
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

foreach ($cmds as $cmd) {
    echo "==> $cmd\n";
    passthru($cmd, $code);
    if ($code !== 0 && !in_array($cmd, ['php artisan storage:link', 'php artisan key:generate --force'])) {
        echo "ERROR en: $cmd (código $code)\n";
        exit($code);
    }
}
PHPEOF

EXPOSE 80

CMD ["php", "/startup.php"]
