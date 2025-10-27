# Use an official PHP + Apache image
FROM php:8.2-apache

# Set working directory
WORKDIR /var/www/html

# Copy everything into the Apache root folder
COPY . /var/www/html/

# Give Apache permission to serve files
RUN chown -R www-data:www-data /var/www/html \
 && chmod -R 755 /var/www/html

# Expose the port Render uses (Render injects $PORT env var)
EXPOSE 10000

# Modify Apache to listen on the Render port
CMD sed -i "s/Listen 80/Listen ${PORT:-10000}/" /etc/apache2/ports.conf && \
    apache2-foreground
