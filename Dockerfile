# syntax=docker/dockerfile:1.4

FROM dunglas/frankenphp:1.0.0-php8.3
WORKDIR /app

# 1. D'abord copier UNIQUEMENT les fichiers nécessaires pour composer
COPY composer.* ./

# 2. Ensuite installer les dépendances système
RUN apt-get update && apt-get install -y \
    acl git gettext && \
    rm -rf /var/lib/apt/lists/*

# 3. Installer les extensions PHP
RUN install-php-extensions \
    @composer \
    apcu \
    intl \
    opcache \
    pdo_pgsql \
    zip

# 4. Installation des dépendances Composer
RUN composer install --no-dev --no-autoloader --no-scripts --no-interaction

# 5. Maintenant copier tout le reste
COPY . .

# 6. Configuration PHP
COPY frankenphp/conf.d/app.ini $PHP_INI_DIR/conf.d/

# 7. Configuration production
RUN set -eux; \
    echo "APP_ENV=prod" > .env; \
    echo "APP_DEBUG=0" >> .env; \
    composer dump-autoload --classmap-authoritative; \
    mkdir -p var/cache var/log; \
    chown -R www-data:www-data var; \
    chmod -R 777 var

# 8. Copie du Caddyfile
COPY frankenphp/Caddyfile /etc/caddy/Caddyfile

EXPOSE $PORT