# Use a base image
FROM ubuntu:20.04

# Set non-interactive mode for apt to avoid any prompts during installation
ENV DEBIAN_FRONTEND=noninteractive

# Install dependencies
RUN apt-get update && apt-get install -y \
    lsb-release \
    ca-certificates \
    apt-transport-https \
    software-properties-common \
    curl \
    nginx \
    supervisor \
    zip unzip \
    php8.2-fpm \
    php8.2-cli \
    php8.2-mysql \
    php8.2-curl \
    php8.2-xml \
    php8.2-mbstring \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Install PHP extensions (ensure this is after PHP is installed)
RUN docker-php-ext-install pdo pdo_mysql

# Configure supervisor to manage processes (nginx, php-fpm)
COPY ./supervisord.conf /etc/supervisor/supervisord.conf
COPY ./nginx.conf /etc/nginx/nginx.conf

# Copy Laravel public folder to the right location
COPY ./public /var/www/html/public

# Expose necessary ports
EXPOSE 80

# Start supervisor to manage processes
CMD ["/usr/bin/supervisord"]
