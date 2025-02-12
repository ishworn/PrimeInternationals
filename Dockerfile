# Use official PHP 8.2 FPM image
FROM php:8.2-fpm

# Install system dependencies and PHP extensions
RUN apt-get update && apt-get install -y \
    nginx \
    supervisor \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libzip-dev \
    unzip \
    git \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd pdo pdo_mysql zip \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Install Composer globally
COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

# Set working directory
WORKDIR /var/www

# Copy Laravel application files
COPY . /var/www

# Install PHP dependencies
RUN composer install --no-dev --prefer-dist --no-scripts --optimize-autoloader

# Set permissions
RUN chown -R www-data:www-data /var/www \
    && chmod -R 775 /var/www/storage /var/www/bootstrap/cache

# Expose PHP-FPM port
EXPOSE 9000
# Expose Nginx port
EXPOSE 80

# Copy Nginx configuration (Make sure you have nginx.conf in your repo)
COPY ./nginx.conf /etc/nginx/nginx.conf

# Copy Supervisor configuration file to manage processes
COPY ./supervisord.conf /etc/supervisor/supervisord.conf

# Start supervisord to run both Nginx and PHP-FPM
CMD ["supervisord", "-c", "/etc/supervisor/supervisord.conf"]

# Use www-data user for security
USER www-data
