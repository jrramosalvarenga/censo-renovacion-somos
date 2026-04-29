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

# Crear script de inicio directamente (sin problemas de CRLF)
RUN printf '#!/bin/bash\nset -e\n\n\
if [ ! -f .env ]; then cp .env.example .env; fi\n\
printenv | grep -E "^(APP_|DB_|DATABASE_URL|SESSION_|CACHE_|QUEUE_|LOG_)" >> .env 2>/dev/null || true\n\
php artisan key:generate --force\n\
php artisan config:clear\n\
sleep 3\n\
php artisan migrate --force\n\
php artisan db:seed --class=DepartamentosSeeder --force\n\
php artisan db:seed --class=CargosSeeder --force\n\
php artisan db:seed --class=MunicipiosSeeder --force\n\
php artisan db:seed --class=LocalidadesSeeder --force\n\
php artisan db:seed --class=AdminUserSeeder --force\n\
php artisan config:cache\n\
php artisan route:cache\n\
php artisan view:cache\n\
php artisan storage:link || true\n\
apache2-foreground\n' > /start.sh && chmod +x /start.sh

EXPOSE 80

CMD ["/bin/bash", "/start.sh"]
