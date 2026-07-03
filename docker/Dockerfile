FROM php:8.3-fpm-alpine

# Install system dependencies
RUN apk add --no-cache \
    git \
    curl \
    libpng-dev \
    libxml2-dev \
    zip \
    unzip \
    libzip-dev \
    icu-dev \
    oniguruma-dev

# Install PHP extensions
RUN docker-php-ext-install \
    intl \
    mbstring \
    mysqli \
    pdo_mysql \
    gd \
    zip \
    xml

# Install Redis extension
RUN apk add --no-cache --virtual .build-deps $PHPIZE_DEPS \
    && pecl install redis \
    && docker-php-ext-enable redis \
    && apk del .build-deps

# Get Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Copy composer config files first to leverage Docker cache
COPY composer.json composer.lock ./

# Install dependencies (no-dev, no-scripts, no-autoloader first to speed up and cache downloads)
RUN composer install --no-interaction --no-scripts --no-autoloader --no-dev

# Copy the rest of the application
COPY . .

# Run composer dump-autoload to optimize the autoloader
RUN composer install --no-interaction --optimize-autoloader --no-dev

# Ensure write permissions for storage/writable directory
RUN chown -R www-data:www-data /var/www/html/writable

EXPOSE 9000
CMD ["php-fpm"]
