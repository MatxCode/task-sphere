#syntax=docker/dockerfile:1.4

# Versions
FROM dunglas/frankenphp:1-php8.3 AS frankenphp_upstream


# Base FrankenPHP image
FROM frankenphp_upstream AS frankenphp_base

WORKDIR /app

# persistent / runtime deps
# hadolint ignore=DL3008
RUN apt-get update && apt-get install -y --no-install-recommends \
	acl \
	file \
	gettext \
	git \
	&& rm -rf /var/lib/apt/lists/*

# Dans la partie où vous installez les extensions PHP
RUN set -eux; \
    install-php-extensions \
        @composer \
        apcu \
        intl \
        opcache \
        pdo_pgsql \
        zip \
    ;

# https://getcomposer.org/doc/03-cli.md#composer-allow-superuser
ENV COMPOSER_ALLOW_SUPERUSER=1

###> recipes ###
###< recipes ###

COPY --link frankenphp/conf.d/app.ini $PHP_INI_DIR/conf.d/
COPY --link --chmod=755 frankenphp/docker-entrypoint.sh /usr/local/bin/docker-entrypoint
COPY --link frankenphp/Caddyfile /etc/caddy/Caddyfile

ENTRYPOINT ["docker-entrypoint"]

HEALTHCHECK --start-period=60s CMD curl -f http://localhost:2019/metrics || exit 1

RUN chmod +x /usr/local/bin/frankenphp && \
    chmod +x /usr/local/bin/docker-php-entrypoint

#CMD ["frankenphp", "run", "--config", "/etc/caddy/Caddyfile"]

# Dev FrankenPHP image
FROM frankenphp_base AS frankenphp_dev

ENV APP_ENV=dev XDEBUG_MODE=off
VOLUME /app/var/

RUN mv "$PHP_INI_DIR/php.ini-development" "$PHP_INI_DIR/php.ini"

RUN set -eux; \
	install-php-extensions \
		xdebug \
	;

COPY --link frankenphp/conf.d/app.dev.ini $PHP_INI_DIR/conf.d/

# Prod FrankenPHP image
FROM frankenphp_base AS frankenphp_prod

ENV APP_ENV=prod
ENV FRANKENPHP_CONFIG="import worker.Caddyfile"

RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"

COPY --link frankenphp/conf.d/app.prod.ini $PHP_INI_DIR/conf.d/
COPY --link frankenphp/worker.Caddyfile /etc/caddy/worker.Caddyfile

# Étape 1: Copier les fichiers nécessaires pour composer
COPY --link composer.* symfony.* ./

# Étape 2: Installer les dépendances sans scripts
RUN set -eux; \
    mkdir -p var/cache var/log; \
    composer install --no-cache --prefer-dist --no-dev --no-autoloader --no-scripts --no-progress; \
    sync;

# Étape 3: Copier tout le code source
COPY --link . ./

# Étape 4: Créer un .env minimal avec toutes les variables REQUISES
RUN { \
    echo "APP_ENV=prod"; \
    echo "APP_SECRET=ChangeThisSecretKeyForProduction!"; \
    echo "DATABASE_URL=postgresql://placeholder:placeholder@placeholder:5432/placeholder"; \
} > .env

# Étape 5: Dump de l'environnement et préparation du cache
RUN set -eux; \
    composer dump-autoload --classmap-authoritative; \
    composer dump-env prod; \
    sync;

# Étape 6: Configurer les permissions
RUN set -eux; \
    chmod +x bin/console; \
    chown -R www-data:www-data var; \
    sync;

# Installer Caddy
RUN curl -fsSL https://caddyserver.com/download/linux/amd64?license=personal -o /usr/local/bin/caddy \
    && chmod +x /usr/local/bin/caddy

# Copie le script de démarrage dans l’image prod
COPY --chmod=755 frankenphp/start.sh /usr/local/bin/start

# Démarre avec le script
CMD ["start"]


# Note: Les commandes cache:clear et cache:warmup seront exécutées au runtime