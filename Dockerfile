# syntax=docker/dockerfile:1.4

FROM dunglas/frankenphp:latest

WORKDIR /app

# Dépendances système
RUN apt-get update && apt-get install -y \
    acl git gettext && \
    rm -rf /var/lib/apt/lists/*

# Extensions PHP
RUN install-php-extensions \
    @composer \
    apcu \
    intl \
    opcache \
    pdo_pgsql \
    zip

# Configuration PHP
COPY frankenphp/conf.d/app.ini $PHP_INI_DIR/conf.d/

# Installation Composer
COPY composer.* ./
RUN composer install --no-dev --no-autoloader --no-scripts --no-interaction

# Copie de l'application
COPY . .

# Configuration production
RUN composer dump-autoload --classmap-authoritative && \
    composer dump-env prod && \
    mkdir -p var/cache var/log && \
    chown -R www-data:www-data var && \
    chmod -R 777 var

# Copie du Caddyfile
COPY frankenphp/Caddyfile /etc/caddy/Caddyfile

EXPOSE $PORT