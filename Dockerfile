# syntax=docker/dockerfile:1.4

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

# Configuration PHP
COPY --link frankenphp/conf.d/app.ini $PHP_INI_DIR/conf.d/

# Composer & Symfony
COPY --link composer.* symfony.* ./
RUN composer install --no-dev --no-interaction --no-scripts --prefer-dist

COPY --link . .

RUN echo "APP_ENV=prod" > .env
RUN composer dump-autoload --classmap-authoritative
RUN composer dump-env prod

RUN mkdir -p var && chmod +x bin/console && chown -R www-data:www-data var

# ... (conservez le début de votre Dockerfile existant)

# Configurez les variables d'environnement pour la production
ENV APP_ENV=prod
ENV APP_DEBUG=0

# Configurez le Caddyfile directement
RUN echo ":${PORT} {\n    root * /app/public\n    php_frankenphp * /index.php\n    file_server\n}" > /etc/caddy/Caddyfile

# Configurez les permissions
RUN mkdir -p var/cache var/log && \
    chown -R www-data:www-data var && \
    chmod -R 777 var

EXPOSE $PORT