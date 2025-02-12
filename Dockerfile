# Use the official PHP 8.2 FPM image as the base image
FROM php:8.2-fpm

# Set non-interactive mode to avoid prompts during package installations
ENV DEBIAN_FRONTEND=noninteractive

# Install system dependencies
RUN apt-get update && apt-get install -y \
    nginx \
    supervisor \
    zip unzip \
    git \
    libzip-dev \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install pdo pdo_mysql mbstring zip exif pcntl bcmath gd

# Install Composer (PHP dependency manager)
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Create log directories for Nginx and PHP-FPM
RUN mkdir -p /var/log/nginx /var/log/php-fpm
RUN chown -R www-data:www-data /var/log/nginx /var/log/php-fpm

# Set the working directory
WORKDIR /var/www/html

# Copy the entire project directory
COPY ./public /var/www/html/public

# Install Composer dependencies
RUN composer install --optimize-autoloader --no-dev -vvv

# Set permissions for Laravel storage and bootstrap/cache
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
RUN chmod -R 755 /var/www/html/storage /var/www/html/bootstrap/cache

# Configure supervisor to manage processes (nginx, php-fpm)
COPY ./supervisord.conf /etc/supervisor/supervisord.conf
COPY ./nginx.conf /etc/nginx/nginx.conf

# Expose necessary ports
EXPOSE 80

# Start supervisor to manage processes
CMD ["/usr/bin/supervisord"]

