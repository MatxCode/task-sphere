# 1. Utilise l'image officielle FrankenPHP
FROM dunglas/frankenphp:1-php8.3 as frankenphp

# 2. Copie les fichiers du projet dans le conteneur
COPY . /app

# 3. Se place dans le dossier de l'application
WORKDIR /app

# 4. Installe les dépendances PHP sans les dev
RUN set -eux; \
    composer install --no-dev --optimize-autoloader; \
    composer dump-autoload --classmap-authoritative; \
    composer dump-env prod; \
    chmod +x bin/console; \
    mkdir -p var/cache var/log

# 5. Donne les bons droits
RUN chown -R www-data:www-data /app/var

# 6. Configuration FrankenPHP (si tu as besoin de fichiers custom)
# COPY frankenphp/Caddyfile /etc/caddy/Caddyfile
# COPY frankenphp/conf.d/app.ini /usr/local/etc/php/conf.d/app.ini

# ✅ Pas besoin de COPY .env.local ou .env
# Toutes les variables doivent être passées via Railway ou ton environnement d'exécution.
