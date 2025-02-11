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
    nginx \
    supervisor \
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

# Copy Nginx config file
COPY nginx.conf /etc/nginx/nginx.conf

# Copy Supervisor config file
COPY supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# Expose HTTP and PHP-FPM ports
EXPOSE 80
EXPOSE 9000

# Start both Nginx and PHP-FPM using Supervisor
CMD ["/usr/bin/supervisord"]
