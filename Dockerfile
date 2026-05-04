# ---------- 1) Build frontend assets ----------
FROM node:20-alpine AS frontend-build
WORKDIR /app

COPY package*.json ./
RUN npm ci

COPY . .
RUN npm run build

# ---------- 2) Install PHP dependencies ----------
FROM composer:2 AS vendor
WORKDIR /app

COPY composer.json composer.lock* ./
RUN composer install --no-dev --no-interaction --prefer-dist --optimize-autoloader

# ---------- 3) Runtime: PHP-FPM + Nginx ----------
FROM php:8.2-fpm-alpine

RUN apk add --no-cache nginx bash icu-dev libzip-dev oniguruma-dev postgresql-dev \
 && docker-php-ext-install pdo pdo_pgsql intl zip mbstring opcache

WORKDIR /var/www/html

# copy app source
COPY . .

# copy vendor
COPY --from=vendor /app/vendor /var/www/html/vendor

# copy built assets (Laravel Vite outputs here)
COPY --from=frontend-build /app/public/build /var/www/html/public/build

# nginx config
COPY docker/nginx/default.conf /etc/nginx/http.d/default.conf

# permissions
RUN mkdir -p storage bootstrap/cache \
 && chown -R www-data:www-data storage bootstrap/cache

EXPOSE 80
CMD ["sh", "-c", "php-fpm -D && nginx -g 'daemon off;'"]
