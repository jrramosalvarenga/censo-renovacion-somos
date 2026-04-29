FROM php:8.3-apache

# Extensiones necesarias + dos2unix para corregir saltos de línea Windows
RUN apt-get update && apt-get install -y \
    libpq-dev libzip-dev libpng-dev libjpeg-dev libfreetype6-dev \
    zip unzip git curl dos2unix \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo pdo_pgsql pgsql zip gd \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Dependencias primero (cache de Docker)
COPY composer.json composer.lock ./
RUN composer install --no-dev --optimize-autoloader --no-scripts

# Copiar código
COPY . .

# Scripts post-install
RUN composer run-script post-autoload-dump 2>/dev/null || true

# Apache: apunta al public/
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' \
    /etc/apache2/sites-available/*.conf \
    /etc/apache2/apache2.conf \
    /etc/apache2/conf-available/*.conf \
    && a2enmod rewrite headers

# Permisos y corregir saltos de línea del script
RUN chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache \
    && dos2unix docker-start.sh \
    && chmod +x docker-start.sh

EXPOSE 80

CMD ["/bin/bash", "/var/www/html/docker-start.sh"]
