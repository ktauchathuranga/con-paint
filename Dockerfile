# Dockerfile
FROM php:8.1-apache

# Install dependencies including libzip-dev for the PHP zip extension
RUN apt-get update && apt-get install -y \
    git \
    zip \
    unzip \
    libzip-dev \
    && rm -rf /var/lib/apt/lists/*

# Enable and install the PHP zip extension
RUN docker-php-ext-install zip

# Copy application files
COPY . /var/www/html

# Expose port 80
EXPOSE 80
