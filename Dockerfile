# Use the official PHP 8.2 image with FPM
FROM php:8.2-fpm

# Install necessary system dependencies
# Use a base image that supports PHP
FROM ubuntu:22.04  

# Set non-interactive frontend for apt-get
ENV DEBIAN_FRONTEND=noninteractive  

# Install required dependencies
RUN apt-get update && apt-get install -y \
    lsb-release ca-certificates apt-transport-https software-properties-common curl && \
    curl -sSL https://packages.sury.org/php/README.txt | bash - && \
    apt-get update && apt-get install -y \
    php8.2-fpm \
    nginx \
    supervisor \
    zip unzip \
    curl \
# Install PHP extensions (Modify as needed)
RUN docker-php-ext-install pdo pdo_mysql

# Copy custom configuration files
COPY nginx.conf /etc/nginx/nginx.conf
COPY supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# Set working directory
WORKDIR /var/www/html

# Expose ports
EXPOSE 80

# Start Supervisor
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]
