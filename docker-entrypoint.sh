#!/usr/bin/env bash
# Replace Apache Listen directive with Render supplied port (default 10000)
PORT=${PORT:-10000}
# Update Apache listen and virtual host
sed -i "s/Listen 80/Listen ${PORT}/g" /etc/apache2/ports.conf || true
sed -i "s/<VirtualHost \*:80>/<VirtualHost \*:${PORT}>/g" /etc/apache2/sites-enabled/000-default.conf || true

# Ensure perms then start Apache in foreground
chown -R www-data:www-data /var/www/html
exec apache2-foreground
