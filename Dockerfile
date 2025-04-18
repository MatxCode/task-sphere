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

# Mise à jour de Flex pour corriger les dépréciations
RUN composer update symfony/flex --no-plugins --no-scripts

# Installation Composer (sans .env)
COPY composer.* ./
RUN composer install --no-dev --no-autoloader --no-scripts --no-interaction

# Copie de l'application (sans .env)
COPY . .

# Configuration production
RUN set -eux; \
    # Crée un .env minimal pour la prod
    echo "APP_ENV=prod" > .env; \
    echo "APP_DEBUG=0" >> .env; \
    echo "DATABASE_URL=\${DATABASE_URL}" >> .env; \
    echo "APP_SECRET=\${APP_SECRET}" >> .env; \
    # Génère l'autoloader
    composer dump-autoload --classmap-authoritative; \
    # Permissions
    mkdir -p var/cache var/log && \
    chown -R www-data:www-data var && \
    chmod -R 777 var

# Copie du Caddyfile
COPY frankenphp/Caddyfile /etc/caddy/Caddyfile

EXPOSE $PORT