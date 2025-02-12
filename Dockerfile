# Use a stable version of Ubuntu
FROM ubuntu:22.04

# Set non-interactive mode to avoid prompts during package installations
ENV DEBIAN_FRONTEND=noninteractive

# Update package list and install essential dependencies
RUN apt-get update && apt-get install -y \
    curl \
    nginx \
    supervisor \
    zip unzip \
    ca-certificates \
    apt-transport-https \
    software-properties-common \
    php-fpm \
    php-cli \
    php-mysql \
    php-curl \
    php-xml \
    php-mbstring \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Install necessary PHP extensions
RUN docker-php-ext-install pdo pdo_mysql

# Configure supervisor to manage processes (nginx, php-fpm)
COPY ./supervisord.conf /etc/supervisor/supervisord.conf
COPY ./nginx.conf /etc/nginx/nginx.conf

# Copy your application code into the container
COPY ./public /var/www/html/public

# Expose necessary ports
EXPOSE 80

# Start supervisor to manage processes
CMD ["/usr/bin/supervisord"]
