FROM php:7.4-fpm

ARG APP_DEBUG
ARG APP_URL

ENV DB_HOST=database
ENV DB_PORT=3306
ENV DB_DATABASE=laravel
ENV DB_USERNAME=root
ENV DB_PASSWORD=root

ENV MG_HOST=mongo
ENV MG_PORT=27017
ENV MG_DATABASE=aedes
ENV MG_USERNAME=admin
ENV MG_PASSWORD=admin

ARG MAIL_HOST
ARG MAIL_PORT
ARG MAIL_USERNAME
ARG MAIL_PASSWORD
ARG MAIL_ENCRYPTION
ARG MAIL_FROM_ADDRESS

# Install dependencies
RUN apt-get update && apt-get install -y libonig-dev libcurl4-openssl-dev pkg-config libssl-dev zip

# Use the default production configuration
RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"

# Install extensions
RUN docker-php-ext-install pdo_mysql mbstring
RUN pecl install mongodb && docker-php-ext-enable mongodb

# Install composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Add user for laravel application
RUN groupadd -g 1000 www
RUN useradd -u 1000 -ms /bin/bash -g www www

# Copy existing application directory permissions
COPY --chown=www:www . /var/www
COPY .env.example /var/www/.env

# Define work path
WORKDIR /var/www

# Install Packages
RUN composer install --optimize-autoloader --no-dev
# Generate APP key
RUN php artisan key:generate

# Install Optimizations
RUN php artisan config:cache
RUN php artisan route:cache
RUN php artisan view:cache

# Change current user to www
USER www

# Expose port 9000 and start php-fpm server
EXPOSE 9000
CMD ["php-fpm"]