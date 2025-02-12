# Use a base image
FROM ubuntu:20.04

# Install dependencies and add repositories
RUN apt-get update && apt-get install -y \
    lsb-release \
    ca-certificates \
    apt-transport-https \
    software-properties-common \
    curl \
    && curl -sSL https://packages.sury.org/php/README.txt | bash - \
    && apt-get update

# Install PHP and necessary packages
RUN apt-get install -y \
    php8.2-fpm \
    php8.2-cli \
    php8.2-mysql \
    php8.2-curl \
    php8.2-xml \
    php8.2-mbstring \
    php8.2-bcmath \
    php8.2-opcache \
    nginx \
    supervisor \
    zip unzip \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Install PHP extensions (ensure this is after PHP is installed)
RUN docker-php-ext-install pdo pdo_mysql

# Configure supervisor to manage processes (nginx, php-fpm)
COPY ./supervisord.conf /etc/supervisor/supervisord.conf
COPY ./nginx.conf /etc/nginx/nginx.conf
COPY ./index.php /var/www/html/index.php

# Copy Laravel public folder to the right location
COPY ./public/index.php /var/www/html/index.php

# Set the correct ownership and permissions for Laravel storage directories
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
RUN chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Expose necessary ports
EXPOSE 80

# Start supervisor to manage processes
CMD ["/usr/bin/supervisord"]
