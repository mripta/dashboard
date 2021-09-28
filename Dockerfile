FROM php:7.4-fpm

ENV APP_DEBUG=true
ENV APP_URL=http://localhost

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

ENV MAIL_HOST=mailhog
ENV MAIL_PORT=1025
ENV MAIL_USERNAME=null
ENV MAIL_PASSWORD=null
ENV MAIL_ENCRYPTION=null
ENV MAIL_FROM_ADDRESS=null

# Install dependencies
RUN apt-get update && apt-get install -y libonig-dev libcurl4-openssl-dev pkg-config libssl-dev

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

# Generate ENV key
RUN composer install
RUN php artisan key:generate

# Change current user to www
USER www

# Expose port 9000 and start php-fpm server
EXPOSE 9000
CMD ["php-fpm"]