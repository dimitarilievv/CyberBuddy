#ONLY FOR TESTING PURPOSES - NOT USED IN PRODUCTION
#UNCOMMENT AND USE IF YOU WANT TO TEST THE APP LOCALLY

#FROM php:8.2-fpm-alpine
#
## OS packages + node (for Laravel Vite builds if needed)
#RUN apk add --no-cache \
#    bash curl git icu-dev libzip-dev oniguruma-dev postgresql-dev \
#    nodejs npm zip unzip
#
## PHP extensions needed for Laravel + Postgres
#RUN docker-php-ext-install pdo pdo_pgsql intl zip mbstring opcache
#
## Composer
#COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
#
#WORKDIR /var/www/html
