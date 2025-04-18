#syntax=docker/dockerfile:1.4

FROM dunglas/frankenphp:1-php8.3

WORKDIR /app

# Installer les dépendances système
RUN apt-get update && apt-get install -y --no-install-recommends \
    acl \
    file \
    gettext \
    git \
 && rm -rf /var/lib/apt/lists/*

# Extensions PHP
RUN install-php-extensions \
    @composer \
    apcu \
    intl \
    opcache \
    pdo_pgsql \
    zip

# Configuration PHP & Caddy
COPY --link frankenphp/conf.d/app.ini $PHP_INI_DIR/conf.d/
COPY --link frankenphp/Caddyfile /etc/caddy/Caddyfile

# Composer & Symfony
COPY --link composer.* symfony.* ./
RUN composer install --no-dev --no-interaction --no-scripts --prefer-dist

COPY --link . .

# Autoriser Composer en root
ENV COMPOSER_ALLOW_SUPERUSER=1

RUN echo "APP_ENV=prod" > .env
RUN composer dump-autoload --classmap-authoritative
RUN composer dump-env prod

RUN mkdir -p var && chmod +x bin/console && chown -R www-data:www-data var
RUN chmod +x bin/console && chown -R www-data:www-data var

# L'image démarre automatiquement avec le bon CMD
