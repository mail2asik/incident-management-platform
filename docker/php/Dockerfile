FROM php:8.3-fpm-alpine

# Install system dependencies & PHP extensions
RUN apk add --no-cache \
    git \
    unzip \
    icu-dev \
    libzip-dev \
    bash \
    mysql-client \
    && docker-php-ext-install \
    intl \
    pdo_mysql \
    zip \
    opcache

# Get modern Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

EXPOSE 9000
CMD ["php-fpm"]