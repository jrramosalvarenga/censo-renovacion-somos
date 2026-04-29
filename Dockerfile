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

# Apache apunta al public/ y escucha en puerto 10000 (Render)
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

# Credenciales Neon y config de producción
ENV DATABASE_URL="postgresql://neondb_owner:npg_tkTFA2DbRLn5@ep-curly-poetry-ano8hn7w.c-6.us-east-1.aws.neon.tech/RenovacionSomos?sslmode=require"
ENV ULTRAMSG_INSTANCE="instance172465"
ENV ULTRAMSG_TOKEN="gg1udf6cwn10vbc1"

# Generar APP_KEY y .env durante el build
RUN APP_KEY=$(php artisan key:generate --show 2>/dev/null | tr -d '[:space:]') && \
    DB_URL="postgresql://neondb_owner:npg_tkTFA2DbRLn5@ep-curly-poetry-ano8hn7w.c-6.us-east-1.aws.neon.tech/RenovacionSomos?sslmode=require" && \
    printf 'APP_NAME="Censo Renovacion Somos"\nAPP_ENV=production\nAPP_KEY=%s\nAPP_DEBUG=false\nDB_CONNECTION=pgsql\nDATABASE_URL=%s\nSESSION_DRIVER=file\nCACHE_STORE=file\nQUEUE_CONNECTION=sync\nLOG_LEVEL=error\nULTRASMG_INSTANCE=instance172465\nULTRASMG_TOKEN=gg1udf6cwn10vbc1\n' \
        "$APP_KEY" "$DB_URL" > /var/www/html/.env

# Script de inicio creado en build (sin CRLF)
RUN printf '#!/bin/sh\n\
echo "==> Iniciando migraciones en segundo plano..."\n\
(\n\
  sleep 2\n\
  php /var/www/html/artisan config:clear\n\
  php /var/www/html/artisan migrate:fresh --force\n\
  php /var/www/html/artisan db:seed --class=DepartamentosSeeder --force\n\
  php /var/www/html/artisan db:seed --class=CargosSeeder --force\n\
  php /var/www/html/artisan db:seed --class=MunicipiosSeeder --force\n\
  php /var/www/html/artisan db:seed --class=LocalidadesSeeder --force\n\
  php /var/www/html/artisan db:seed --class=AdminUserSeeder --force\n\
  php /var/www/html/artisan config:cache\n\
  php /var/www/html/artisan route:cache\n\
  php /var/www/html/artisan view:cache\n\
  php /var/www/html/artisan storage:link\n\
  echo "==> Setup completado"\n\
) &\n\
echo "==> Iniciando Apache en puerto 10000..."\n\
exec apache2-foreground\n' > /entrypoint.sh && chmod +x /entrypoint.sh

EXPOSE 10000

CMD ["/bin/sh", "/entrypoint.sh"]
