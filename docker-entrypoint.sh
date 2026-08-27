#!/bin/sh
set -e

# Cache configuration, routes, and views for high production performance
php artisan storage:link --force || true
php artisan config:cache || true
php artisan route:cache || true
php artisan view:cache || true

# Run database migrations automatically in production if database is reachable
if [ "$RUN_MIGRATIONS" = "true" ]; then
    echo "Running database migrations..."
    php artisan migrate --force || true
fi

# Execute the main container command (apache2-foreground)
exec "$@"
