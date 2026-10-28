#!/bin/sh
set -e

echo "=== Starting Dearly Platform on Render ==="

# Adjust Nginx port dynamically if Render injects $PORT (Render default is 10000 or custom)
PORT="${PORT:-80}"
echo "Configuring Nginx port to: ${PORT}"
sed -i "s/listen [0-9]\+;/listen ${PORT};/g" /etc/nginx/conf.d/default.conf || true
sed -i "s/listen \[::\]:[0-9]\+;/listen [::]:${PORT};/g" /etc/nginx/conf.d/default.conf || true

# Fix storage and cache permissions
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Ensure storage symlink exists
php artisan storage:link --force || true

# Run database migrations if any pending
echo "Checking database migrations..."
php artisan migrate --force || true

# Cache configurations, routes, and views for optimal production performance
echo "Caching configuration and routes..."
php artisan config:cache || true
php artisan route:cache || true
php artisan view:cache || true

# Start PHP-FPM daemon
echo "Starting PHP-FPM daemon..."
php-fpm -D

# Start background queue worker (processes Brevo emails and letter deliveries)
echo "Starting background queue worker..."
php artisan queue:work --sleep=3 --tries=3 &

# Start Nginx in foreground to keep container running
echo "Starting Nginx web server..."
exec nginx -g "daemon off;"
