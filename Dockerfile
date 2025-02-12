# Use official PHP 8.2 FPM image
FROM php:8.2-fpm

# Install system dependencies and PHP extensions
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libzip-dev \
    unzip \
    git \
    supervisor \
    nginx \
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

# Set permissions for Laravel storage and cache directories
RUN chown -R www-data:www-data /var/www \
    && chmod -R 775 /var/www/storage /var/www/bootstrap/cache

# Create necessary log directories
RUN mkdir -p /var/log/php-fpm && touch /var/log/php-fpm/access.log /var/log/php-fpm/error.log

# Expose the necessary ports
EXPOSE 80 9000

# Use www-data user for security
USER www-data

# Copy Supervisor configuration file
COPY supervisord.conf /etc/supervisor/supervisord.conf

# Start Supervisor which will manage both Nginx and PHP-FPM
CMD ["/usr/bin/supervisord"]
