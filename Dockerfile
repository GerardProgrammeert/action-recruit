FROM composer:2.6.5 AS composer
FROM php:8.4-fpm-alpine
COPY --from=composer /usr/bin/composer /usr/bin/composer

# Install system dependencies
RUN apk add --no-cache \
    make \
    freetype-dev \
    libjpeg-turbo-dev \
    libpng-dev \
    libzip-dev \
    zip \
    unzip \
    nano \
    mysql-client \
    && docker-php-ext-configure gd \
        --with-freetype=/usr/include/ \
        --with-jpeg=/usr/include/ \
    && docker-php-ext-install -j$(nproc) gd pdo pdo_mysql zip

# Expose port 9000 for PHP-FPM
EXPOSE 9000

# Match WSL's UID
ARG UID=1000
RUN adduser -D -u $UID -s /bin/bash php
USER php

# Start PHP-FPM
CMD ["php-fpm"]
