# Start from an official PHP image
FROM php:8.2-fpm

# Install necessary system dependencies
RUN apt-get update && apt-get install -y \
    nginx \
    supervisor \
    zip unzip \
    curl \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Install PHP extensions (modify as needed)
RUN docker-php-ext-install pdo pdo_mysql

# Set working directory
WORKDIR /var/www

# Copy Laravel application files
COPY . .

# Copy Nginx and Supervisor config files (if you have custom ones)
COPY ./nginx.conf /etc/nginx/nginx.conf
COPY ./supervisord.conf /etc/supervisor/supervisord.conf

# Copy the public directory to the container (this includes index.php)
COPY ./public /var/www/html/public

# Set file permissions (adjust as needed for your project)
RUN chown -R www-data:www-data /var/www

# Expose ports
EXPOSE 80 443

# Start Supervisor to manage Nginx and PHP-FPM
CMD ["/usr/bin/supervisord"]
