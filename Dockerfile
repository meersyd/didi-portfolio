# syntax=docker/dockerfile:1

FROM node:22-alpine AS assets
WORKDIR /app
COPY package.json package-lock.json ./
RUN npm ci
COPY vite.config.js ./
COPY resources ./resources
COPY public ./public
RUN npm run build

FROM composer:2 AS vendor
WORKDIR /app
COPY composer.json composer.lock ./
RUN composer install --no-dev --no-scripts --no-autoloader --prefer-dist
COPY . .
RUN composer dump-autoload --optimize --no-dev --classmap-authoritative

FROM php:8.3-fpm-alpine

RUN apk add --no-cache \
        nginx \
        bash \
        icu-dev \
        libzip-dev \
        oniguruma-dev \
        postgresql-dev \
        linux-headers \
    && docker-php-ext-install -j$(nproc) \
        intl \
        pdo \
        pdo_pgsql \
        opcache \
        zip \
        bcmath \
        pcntl \
    && rm -rf /var/cache/apk/*

WORKDIR /var/www/html

COPY docker/php.ini /usr/local/etc/php/conf.d/zz-production.ini
COPY docker/www.conf /usr/local/etc/php-fpm.d/zz-www.conf
COPY docker/nginx.conf /etc/nginx/http.d/default.conf
COPY docker/start.sh /usr/local/bin/start.sh

COPY --from=vendor /app /var/www/html
COPY --from=assets /app/public/build /var/www/html/public/build

RUN mkdir -p \
        storage/framework/cache/data \
        storage/framework/sessions \
        storage/framework/views \
        storage/logs \
        bootstrap/cache \
        /run/nginx \
    && chown -R www-data:www-data storage bootstrap/cache \
    && chmod +x /usr/local/bin/start.sh

ENV APP_ENV=production \
    APP_DEBUG=false \
    LOG_CHANNEL=stderr \
    LOG_STACK=stderr

EXPOSE 10000

CMD ["/usr/local/bin/start.sh"]
