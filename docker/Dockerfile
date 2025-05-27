FROM php:8.2-fpm

RUN docker-php-ext-install fileinfo
RUN echo "upload_max_filesize = 2M" > /usr/local/etc/php/conf.d/uploads.ini
RUN echo "post_max_size = 2M" >> /usr/local/etc/php/conf.d/uploads.ini
RUN echo "display_errors = On" >> /usr/local/etc/php/conf.d/errors.ini
RUN echo "display_startup_errors = On" >> /usr/local/etc/php/conf.d/errors.ini
RUN echo "error_reporting = E_ALL" >> /usr/local/etc/php/conf.d/errors.ini
RUN echo "memory_limit = 256M" >> /usr/local/etc/php/conf.d/memory.ini
RUN echo "max_execution_time = 30" >> /usr/local/etc/php/conf.d/time.ini

# Install dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpq-dev \
    libzip-dev \
    unzip \
    zip

# Install PHP extentsions
RUN docker-php-ext-install \
    pdo \
    pdo_pgsql

# Install composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Set working directory
WORKDIR /var/www/html

# Copy existing application directory contents
COPY website /var/www/html

# Install Laravel dependencies
RUN composer install --no-interaction --optimize-autoloader --no-dev

# Set permissions
RUN chown -R www-data:www-data /var/www/html/storage
RUN chmod -R 775 /var/www/html/storage
# Start PHP-FPM
CMD ["php-fpm"]