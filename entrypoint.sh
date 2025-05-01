#!/bin/bash

set -e

echo " Starting container..."

if [ ! -f "vendor/autoload.php" ]; then
    echo " Installing PHP dependencies via Composer..."
    composer install --no-interaction --optimize-autoloader --no-scripts
else
    echo " Dependencies already installed."
fi

if [ ! -f ".env" ] && [ -f ".env.example" ]; then
    echo " Copying .env.example to .env"
    cp .env.example .env
fi

if ! grep -q "APP_KEY=base64" .env; then
    echo " Generating APP_KEY..."
    php artisan key:generate
fi

if [ ! -f "database/database.sqlite" ]; then
    echo "🗃️ Creating SQLite database..."
    touch database/database.sqlite
fi

if [ ! -s "database/database.sqlite" ]; then
    echo " Running migrations (empty SQLite)..."
    php artisan migrate --force
else
    echo " SQLite DB exists, skipping migrations."
fi

echo "Fixing permissions..."
chmod -R 775 storage bootstrap/cache || true

echo " Starting Apache..."
exec apache2-foreground
