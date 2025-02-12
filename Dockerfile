# Use the official PHP 8.2 image with FPM
FROM php:8.2-fpm

# Install required packages
RUN apt-get update && apt-get install -y \
    nginx \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libzip-dev \
    unzip \
    git \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd pdo pdo_mysql zip \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

# Set working directory
WORKDIR /var/www

# Copy Laravel application files
COPY . /var/www

# Install PHP dependencies using Composer
RUN composer install --no-dev --prefer-dist --no-scripts --optimize-autoloader

# Set permissions for Laravel app directories
RUN chown -R www-data:www-data /var/www \
    && chmod -R 775 /var/www/storage /var/www/bootstrap/cache

# Copy Nginx configuration file
COPY ./nginx.conf /etc/nginx/conf.d/default.conf

# Expose ports (Nginx serves on port 80)
EXPOSE 80

# Start both PHP-FPM and Nginx
CMD service php8.2-fpm start && nginx -g 'daemon off;'

