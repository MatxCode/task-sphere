FROM php:8.3-fpm

# 1. Installe les extensions PHP nécessaires
RUN apt-get update && apt-get install -y \
    git unzip zip libicu-dev libpq-dev libonig-dev libzip-dev \
    && docker-php-ext-install intl pdo pdo_mysql zip opcache

# 2. Installe Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# 3. Copie les fichiers du projet
WORKDIR /var/www
COPY . .

# 4. Installe les dépendances PHP (sans dev)
RUN composer install --no-dev --optimize-autoloader \
    && composer dump-env prod \
    && chmod +x bin/console

# 5. Crée les dossiers cache/log
RUN mkdir -p var/cache var/log && chown -R www-data:www-data var

# 6. Utilise l'utilisateur www-data
USER www-data
