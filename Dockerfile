# ==============================================================================
# Stage 1: Build Frontend Assets
# ==============================================================================
FROM node:20-alpine AS frontend
WORKDIR /app

COPY package*.json ./
RUN npm ci || npm install

COPY resources/ ./resources/
COPY vite.config.js tailwind.config.js postcss.config.js ./

RUN npm run build

# ==============================================================================
# Stage 2: Production PHP 8.2-FPM + Nginx Container
# ==============================================================================
FROM php:8.2-fpm-alpine

# Set working directory
WORKDIR /var/www/html

# Install system utilities, build libraries, Nginx, and CA certificates
RUN apk update && apk add --no-cache \
    nginx \
    curl \
    git \
    unzip \
    libpng-dev \
    libjpeg-turbo-dev \
    freetype-dev \
    libzip-dev \
    icu-dev \
    sed \
    ca-certificates \
    && update-ca-certificates

# Configure and install PHP extensions needed by Laravel 11 & Dearly
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) \
        pdo_mysql \
        bcmath \
        gd \
        intl \
        zip \
        opcache

# Recommended production PHP settings
RUN { \
    echo 'opcache.enable=1'; \
    echo 'opcache.memory_consumption=128'; \
    echo 'opcache.interned_strings_buffer=16'; \
    echo 'opcache.max_accelerated_files=10000'; \
    echo 'opcache.validate_timestamps=0'; \
    echo 'opcache.revalidate_freq=0'; \
    echo 'upload_max_filesize=25M'; \
    echo 'post_max_size=25M'; \
    echo 'memory_limit=256M'; \
} > /usr/local/etc/php/conf.d/custom-production.ini

# Get Composer from official image
COPY --from=composer:2.7 /usr/bin/composer /usr/bin/composer

# Copy composer manifest files to cache dependency installation
COPY composer.json composer.lock ./

# Install production PHP dependencies without running scripts
RUN composer install --no-dev --no-interaction --no-scripts --no-autoloader --prefer-dist

# Copy application source code
COPY . /var/www/html

# Copy compiled frontend assets from Stage 1
COPY --from=frontend /app/public/build /var/www/html/public/build

# Complete optimized composer autoloader
RUN composer dump-autoload --optimize --no-dev

# Configure Nginx virtual host
COPY docker/nginx.conf /etc/nginx/conf.d/default.conf

# Strip any Windows CRLF endings and set execution rights on entrypoint
RUN sed -i 's/\r$//' /var/www/html/docker/entrypoint.sh \
    && chmod +x /var/www/html/docker/entrypoint.sh

# Set correct storage and cache directory ownership
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Expose container port (Default 80, dynamically handled by entrypoint on Render)
EXPOSE 80

# Execute entrypoint
ENTRYPOINT ["/var/www/html/docker/entrypoint.sh"]
