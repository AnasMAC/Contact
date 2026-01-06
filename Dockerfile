FROM php:8.2-apache

# 1. Install system dependencies (Git, Zip, Unzip are needed for Composer)
RUN apt-get update && apt-get install -y \
    git \
    zip \
    unzip \
    libzip-dev \
    libonig-dev \
    libxml2-dev

# 2. Install PHP extensions required by Laravel
RUN docker-php-ext-install pdo_mysql mbstring zip xml

# 3. Enable Apache mod_rewrite
RUN a2enmod rewrite

# 4. Configure Apache DocumentRoot to point to /public
# This allows the environment variable APACHE_DOCUMENT_ROOT to actually work
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# 5. Install Composer directly inside the image
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 6. Copy application source
COPY . /var/www/html/

# 7. Set permissions for Laravel (storage and cache need to be writable)
RUN ls -la /var/www/html/ 
RUN mkdir -p /var/www/html/storage \
    mkdir -p /var/www/html/bootstrap/cache
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 775 /var/www/html/storage \
    && chmod -R 775 /var/www/html/bootstrap/cache