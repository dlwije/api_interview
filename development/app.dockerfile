FROM php:8.2-fpm

COPY composer.lock composer.json /var/www/
COPY database /var/www/database

WORKDIR /var/www

# Install required system dependencies
RUN apt-get update && \
    apt-get install -y libmcrypt-dev libmagickwand-dev unzip zlib1g-dev --no-install-recommends && \
    apt-get clean && \
    rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install pdo_mysql zip
RUN pecl install imagick

# Install Composer
RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
    && php composer-setup.php --install-dir=/usr/local/bin --filename=composer \
    && php -r "unlink('composer-setup.php');"

# Install Composer dependencies
RUN composer install --no-dev --no-scripts

# Copy the rest of the application code
COPY . /var/www

# Set proper permissions
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache

# Clear cache and optimize Laravel
RUN php artisan cache:clear && php artisan optimize

# Rename the environment file
RUN mv .env.prod .env

RUN php artisan optimize
