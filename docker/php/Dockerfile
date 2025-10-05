FROM php:8.4-fpm

ARG UID=1000
ARG GID=1000

# системные зависимости, утилиты и библиотеки для PHP-расширений
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    curl \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libicu-dev \
    libxml2-dev \
    libzip-dev \
    libpq-dev \
    libonig-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) \
        pdo_mysql \
        pdo_pgsql \
        mbstring \
        xml \
        intl \
        zip \
        gd \
        opcache \
        bcmath \
        exif \
        pcntl \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Устанавливаем phpredis (PECL)
RUN pecl install redis \
 && docker-php-ext-enable redis

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

COPY ./docker/php/php.ini /usr/local/etc/php/conf.d/php-custom.ini

WORKDIR /data

# COPY ../../composer.json ./

# RUN composer install 

CMD ["php-fpm"]