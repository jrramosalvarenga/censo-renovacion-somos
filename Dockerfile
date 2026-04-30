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

# Usar .env.production como .env
RUN cp .env.production .env && php artisan key:generate --force

# Apache: apunta al public/ y escucha en puerto 10000 (Render)
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' \
        /etc/apache2/sites-available/*.conf \
        /etc/apache2/apache2.conf \
        /etc/apache2/conf-available/*.conf \
    && a2enmod rewrite headers \
    && sed -i 's/Listen 80/Listen 10000/' /etc/apache2/ports.conf \
    && sed -i 's/<VirtualHost \*:80>/<VirtualHost *:10000>/' /etc/apache2/sites-available/000-default.conf

# Permisos
RUN chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

# Script de inicio: Apache arranca de inmediato, migraciones en segundo plano
RUN printf '#!/bin/sh\n\
echo "==> Ejecutando setup en segundo plano..."\n\
(\n\
  sleep 2\n\
  cd /var/www/html\n\
  php artisan migrate:fresh --force\n\
  php artisan db:seed --class=DepartamentosSeeder --force\n\
  php artisan db:seed --class=CargosSeeder --force\n\
  php artisan db:seed --class=MunicipiosSeeder --force\n\
  php artisan db:seed --class=LocalidadesSeeder --force\n\
  php artisan db:seed --class=AdminUserSeeder --force\n\
  php artisan config:cache\n\
  php artisan route:cache\n\
  php artisan view:cache\n\
  php artisan storage:link || true\n\
  echo "==> Setup completado OK"\n\
) &\n\
echo "==> Apache iniciando en puerto 10000"\n\
exec apache2-foreground\n' > /entrypoint.sh && chmod +x /entrypoint.sh

EXPOSE 10000

CMD ["/bin/sh", "/entrypoint.sh"]
