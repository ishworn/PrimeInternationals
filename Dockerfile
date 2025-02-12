# Use a more recent stable version of Ubuntu
FROM ubuntu:22.04

# Set non-interactive mode to avoid prompts during package installations
ENV DEBIAN_FRONTEND=noninteractive

# Update base repositories and install essential dependencies
RUN apt-get update && apt-get install -y \
    curl \
    nginx \
    supervisor \
    zip unzip \
    ca-certificates \
    apt-transport-https \
    software-properties-common \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Add the Sury PHP repository
RUN curl -sSL https://packages.sury.org/php/README.txt | bash -

# Update package lists after adding PHP repository
RUN apt-get update

# Install PHP and PHP extensions
RUN apt-get install -y \
    php8.2-fpm \
    php8.2-cli \
    php8.2-mysql \
    php8.2-curl \
    php8.2-xml \
    php8.2-mbstring \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Install necessary PHP extensions after PHP is installed
RUN docker-php-ext-install pdo pdo_mysql

# Configure supervisor to manage processes (nginx, php-fpm)
COPY ./supervisord.conf /etc/supervisor/supervisord.conf
COPY ./nginx.conf /etc/nginx/nginx.conf

# Copy the Laravel public folder to the correct location
COPY ./public /var/www/html/public

# Expose necessary ports
EXPOSE 80

# Start supervisor to manage processes
CMD ["/usr/bin/supervisord"]
