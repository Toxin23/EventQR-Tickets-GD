FROM php:8.1-apache

RUN apt-get update && apt-get install -y \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    git \
    curl \
    libmariadb-dev \
    libmariadb-dev-compat

RUN docker-php-ext-install pdo_mysql
RUN a2enmod rewrite

WORKDIR /var/www/html
COPY . .
RUN chown -R www-data:www-data /var/www/html

EXPOSE 80
