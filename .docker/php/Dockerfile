FROM php:8.1-apache

RUN apt-get update && apt-get install -y \
    curl \
    zip \
    unzip \
    git \
    libpq-dev \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libonig-dev \
    libzip-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd pdo pdo_mysql mysqli zip bcmath

RUN a2enmod rewrite
WORKDIR /var/www/html
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
COPY .docker/apache/000-default.conf /etc/apache2/sites-available/000-default.conf