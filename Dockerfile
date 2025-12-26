FROM php:8.3-fpm-alpine

RUN apk add --no-cache \
    bash \
    git \
    unzip \
    icu-dev \
    oniguruma-dev \
    libzip-dev \
    postgresql-dev \
    $PHPIZE_DEPS

RUN docker-php-ext-install \
    pdo_pgsql \
    pgsql \
    mbstring \
    zip \
    intl \
    opcache

RUN pecl install redis \
    && docker-php-ext-enable redis

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

RUN addgroup -g 1000 -S www \
    && adduser  -u 1000 -S www -G www

WORKDIR /var/www/html

USER www
