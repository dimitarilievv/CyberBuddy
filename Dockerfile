# ---------- 1) Build frontend assets ----------
FROM node:20-alpine AS frontend-build
WORKDIR /app

COPY package*.json ./
RUN npm ci

COPY . .
RUN npm run build

# ---------- 2) Install PHP dependencies (needs artisan for package:discover) ----------
FROM php:8.2-cli-alpine AS vendor

RUN apk add --no-cache git icu-dev libzip-dev oniguruma-dev postgresql-dev \
 && docker-php-ext-install pdo pdo_pgsql intl zip mbstring exif

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /app
COPY . .
RUN composer install --no-dev --no-interaction --prefer-dist --optimize-autoloader

# ---------- 3) Runtime: PHP-FPM + Nginx ----------
FROM php:8.2-fpm-alpine

RUN apk add --no-cache nginx bash icu-dev libzip-dev oniguruma-dev postgresql-dev \
 && docker-php-ext-install pdo pdo_pgsql intl zip mbstring opcache exif

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

CMD ["sh", "-c", "php artisan optimize:clear || true; php artisan migrate --force -v || true; if [ \"$RUN_SEED\" = \"true\" ]; then php artisan db:seed --force -v; fi; php-fpm -D && nginx -g 'daemon off;'"]
