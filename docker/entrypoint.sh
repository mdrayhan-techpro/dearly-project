#!/bin/sh
set -e

echo "=== Starting Dearly Platform on Render ==="

# Adjust Nginx port dynamically if Render injects $PORT (Render default is 10000 or custom)
PORT="${PORT:-80}"
echo "Configuring Nginx port to: ${PORT}"
sed -i "s/listen [0-9]\+;/listen ${PORT};/g" /etc/nginx/http.d/default.conf /etc/nginx/conf.d/default.conf 2>/dev/null || true
sed -i "s/listen \[::\]:[0-9]\+;/listen [::]:${PORT};/g" /etc/nginx/http.d/default.conf /etc/nginx/conf.d/default.conf 2>/dev/null || true

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

# Start Process Manager (Supervisord manages Nginx, PHP-FPM, and Queue Worker with auto-restart)
echo "Starting Supervisord (Nginx + PHP-FPM + Queue Worker)..."
exec /usr/bin/supervisord -c /etc/supervisord.conf
