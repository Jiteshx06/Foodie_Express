# Use an official PHP + Apache base
FROM php:8.2-apache

# Set working dir
WORKDIR /var/www/html

# Copy app into container
COPY ./app/ /var/www/html/

# Install common PHP extensions (modify as needed)
RUN apt-get update && apt-get install -y \
    libpng-dev libonig-dev libzip-dev zip unzip git \
  && docker-php-ext-install pdo_mysql mbstring zip exif pcntl \
  && rm -rf /var/lib/apt/lists/*

# Composer (if your project uses composer)
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
RUN if [ -f composer.json ]; then composer install --no-dev --optimize-autoloader --no-interaction; fi

# Make sure permissions are correct (adjust user as needed)
RUN chown -R www-data:www-data /var/www/html \
 && chmod -R 755 /var/www/html

# Use an entrypoint script to set Apache to listen on Render's port
COPY docker-entrypoint.sh /usr/local/bin/docker-entrypoint.sh
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

EXPOSE 10000
CMD ["/usr/local/bin/docker-entrypoint.sh"]
