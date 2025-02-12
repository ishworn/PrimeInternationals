# Use official PHP 8.2 image with FPM
FROM php:8.2-fpm

# Install necessary dependencies
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

# Set environment variables for configuration files
ENV SUPERVISORD_CONF /etc/supervisor/conf.d/supervisord.conf
ENV NGINX_CONF /etc/nginx/nginx.conf
ENV PHP_FPM_CONF /etc/php/8.2/fpm/php-fpm.conf

# Set working directory
WORKDIR /var/www

# Copy Laravel application files
COPY . /var/www

# Install PHP dependencies using Composer
COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer
RUN composer install --no-dev --prefer-dist --no-scripts --optimize-autoloader

# Set permissions for Laravel app directories
RUN chown -R www-data:www-data /var/www \
    && chmod -R 775 /var/www/storage /var/www/bootstrap/cache

# Copy configuration files for Supervisor and Nginx
COPY supervisord.conf $SUPERVISORD_CONF
COPY nginx.conf $NGINX_CONF

# Expose required ports
EXPOSE 80
EXPOSE 9000

# Start Supervisor, which will manage PHP-FPM and Nginx
CMD ["/usr/bin/supervisord", "-c", "$SUPERVISORD_CONF"]
