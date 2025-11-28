FROM php:8.4-cli-alpine

# Install dependencies
RUN apk add --no-cache \
    git \
    unzip \
    postgresql-dev \
    autoconf \
    dpkg-dev \
    dpkg \
    file \
    g++ \
    gcc \
    libc-dev \
    make \
    pkgconf \
    re2c

# Install PHP extensions
RUN docker-php-ext-install pdo pdo_pgsql opcache

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Copy composer files
COPY composer.json ./

# Install dependencies (update if lock file is missing)
RUN if [ -f composer.lock ]; then \
        composer install --prefer-dist --no-interaction; \
    else \
        composer update --prefer-dist --no-interaction; \
    fi

# Copy application files
COPY . .

# Create data directory
RUN mkdir -p data

# Set permissions
RUN chown -R www-data:www-data /var/www/html

# Expose port
EXPOSE 8000

# Start Symfony server
USER www-data
CMD ["php", "-S", "0.0.0.0:8000", "-t", "public"]

