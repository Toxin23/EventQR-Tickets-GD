# Use PHP 8.2 with Apache
FROM php:8.2-apache

# Install system dependencies
RUN apt-get update && apt-get install -y \
    unzip \
    git \
    libpng-dev \
    libzip-dev \
    zip \
    curl \
    libonig-dev \
    libxml2-dev \
    libssl-dev \
    libcurl4-openssl-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libmcrypt-dev \
    libreadline-dev \
    libedit-dev \
    libsqlite3-dev \
    libpq-dev \
    libicu-dev \
    libxslt1-dev \
    libtidy-dev \
    default-mysql-client

# Install PHP extensions
RUN docker-php-ext-install pdo pdo_mysql gd zip

# Install Composer globally
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Enable Apache mod_rewrite
RUN a2enmod rewrite

# Set working directory
WORKDIR /var/www/html

# Copy project files into container
COPY . /var/www/html

# Set permissions
RUN chown -R www-data:www-data /var/www/html

# Expose port 80
EXPOSE 80
