FROM php:8.2-apache

# Install dependencies
RUN apt-get update && apt-get install -y \
    libxml2-dev \
    libonig-dev \
    libzip-dev \
    libpng-dev \
    zip \
    unzip \
    && docker-php-ext-install pdo_mysql mbstring xml zip gd

# Set the working directory
WORKDIR /var/www/html

# Copy application files
COPY . .

# Install Composer dependencies
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN composer install --optimize-autoloader --no-dev

# Set permissions for Laravel storage
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Expose Apache port
EXPOSE 80

# Start Apache (No NGINX or PHP-FPM)
CMD ["apache2-foreground"]
