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

// Mostrar todas las variables recibidas
echo "==> Variables de entorno detectadas:\n";
foreach (getenv() as $k => $v) {
    if (preg_match('/^(APP_|DB|PG|DATABASE|SESSION|CACHE|QUEUE|LOG_|ULTRA|GOOGLE)/', $k)) {
        $safe = preg_match('/password|token|secret|key/i', $k) ? '***' : substr($v, 0, 70);
        echo "    $k=$safe\n";
    }
}

// Obtener DATABASE_URL — acepta DATABASE_URL o variables PG* de Neon
$DATABASE_URL = getenv('DATABASE_URL') ?: '';

if (empty($DATABASE_URL)) {
    // Intentar construir desde variables individuales de Neon (PGHOST, PGUSER, etc.)
    $pghost = getenv('PGHOST')     ?: '';
    $pguser = getenv('PGUSER')     ?: '';
    $pgpass = getenv('PGPASSWORD') ?: '';
    $pgdb   = getenv('PGDATABASE') ?: '';
    $pgport = getenv('PGPORT')     ?: '5432';

    if ($pghost && $pguser && $pgpass && $pgdb) {
        $DATABASE_URL = "postgresql://{$pguser}:{$pgpass}@{$pghost}:{$pgport}/{$pgdb}?sslmode=require";
        echo "==> DATABASE_URL construida desde variables PG*\n";
    }
}

if (empty($DATABASE_URL)) {
    echo "\n[ERROR] No se encontro DATABASE_URL ni variables PGHOST/PGUSER/PGPASSWORD/PGDATABASE.\n";
    echo "Agrega en Render > Environment:\n";
    echo "DATABASE_URL=postgresql://neondb_owner:PASS@HOST/DATABASE?sslmode=require\n\n";
    exit(1);
}

echo "==> DATABASE_URL: " . substr($DATABASE_URL, 0, 60) . "...\n";

// Construir .env limpio
$env = [
    'APP_NAME'          => getenv('APP_NAME')         ?: 'Censo Renovacion Somos',
    'APP_ENV'           => getenv('APP_ENV')           ?: 'production',
    'APP_KEY'           => getenv('APP_KEY')           ?: '',
    'APP_DEBUG'         => getenv('APP_DEBUG')         ?: 'false',
    'APP_URL'           => getenv('APP_URL')           ?: 'http://localhost',
    'DB_CONNECTION'     => 'pgsql',
    'DATABASE_URL'      => $DATABASE_URL,
    'SESSION_DRIVER'    => getenv('SESSION_DRIVER')    ?: 'file',
    'CACHE_STORE'       => getenv('CACHE_STORE')       ?: 'file',
    'QUEUE_CONNECTION'  => getenv('QUEUE_CONNECTION')  ?: 'sync',
    'LOG_LEVEL'         => getenv('LOG_LEVEL')         ?: 'error',
    'ULTRAMSG_INSTANCE' => getenv('ULTRAMSG_INSTANCE') ?: '',
    'ULTRAMSG_TOKEN'    => getenv('ULTRAMSG_TOKEN')    ?: '',
];

$content = '';
foreach ($env as $k => $v) {
    // Valores con espacios o caracteres especiales van entre comillas
    $content .= $k . '=' . (strpbrk($v, ' #"\'') !== false ? '"' . addslashes($v) . '"' : $v) . "\n";
}

file_put_contents('/var/www/html/.env', $content);
echo "==> .env creado\n";

// Ejecutar comandos de arranque
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
        echo "==> ERROR en: $cmd (codigo $code)\n";
        exit($code);
    }
}
PHPEOF

EXPOSE 80

CMD ["php", "/startup.php"]
