FROM php:8.2-fpm

WORKDIR /var/www

RUN apt-get update && apt-get install -y \
    apt-utils \
    libpq-dev \
    libmagickwand-dev \
    libpng-dev \
    libzip-dev \
    zip unzip \
    libicu-dev\
    git && \
    pecl install igbinary && \
    pecl install xdebug && \
    docker-php-ext-enable igbinary && \
    docker-php-ext-enable xdebug && \
    pecl install redis && \
    pecl install imagick && \
    docker-php-ext-enable imagick && \
    docker-php-ext-enable redis && \
    docker-php-ext-configure intl && \
    docker-php-ext-install intl bcmath gd zip pdo pdo_pgsql pgsql && \
    apt-get clean && \
    rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

RUN curl -sS https://getcomposer.org/installer \
    | php -- --install-dir=/usr/local/bin --filename=composer

COPY php.ini /usr/local/etc/php/conf.d/php.ini