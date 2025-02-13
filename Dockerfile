# Use PHP-FPM as the base image
FROM php:8.2-fpm

# Install dependencies and PHP extensions
RUN apt-get update && apt-get install -y \
    libxml2-dev \
    libonig-dev \
    libzip-dev \
    libpng-dev \
    nginx \
    zip \
    unzip \
    && docker-php-ext-install pdo_mysql mbstring xml zip gd

# Set the working directory for the application
WORKDIR /var/www/html

# Copy Laravel application files to the container
COPY . .

# Install Composer and Laravel dependencies
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN composer install --optimize-autoloader --no-dev

# Set permissions for Laravel storage and bootstrap/cache
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Copy your custom nginx.conf from the docker folder into the container's Nginx config directory
COPY docker/nginx.conf /etc/nginx/nginx.conf

# Expose ports
EXPOSE 80

# Start Nginx and PHP-FPM
CMD service nginx start && php-fpm
