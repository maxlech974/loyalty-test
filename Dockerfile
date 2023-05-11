FROM php:8.2-fpm

RUN apt-get update && apt-get install -y \
    git \
    zip \
    unzip \
    && pecl install xdebug \
    && docker-php-ext-enable xdebug \
    && docker-php-ext-install pdo_mysql

RUN docker-php-ext-enable pdo_mysql

COPY --from=composer:latest --chown=1000:1000 /usr/bin/composer /usr/local/bin/composer
COPY ./php/xdebug.ini /usr/local/etc/php/conf.d/xdebug.ini

WORKDIR /var/www/html