#!/bin/sh
set -e

echo ">> Migration de la base de donnees..."
php artisan migrate --force --no-interaction

echo ">> Lien storage..."
php artisan storage:link 2>/dev/null || true

echo ">> Cache de configuration..."
php artisan config:cache
php artisan route:cache 2>/dev/null || true
php artisan view:cache 2>/dev/null || true

echo ">> Demarrage du serveur sur le port ${PORT:-8080}..."
exec php artisan serve --host=0.0.0.0 --port="${PORT:-8080}"
