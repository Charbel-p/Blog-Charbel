#!/bin/sh
PORT="${PORT:-8080}"

echo "=== Blog Charbel - demarrage port $PORT ==="

php artisan config:clear 2>/dev/null || true
php artisan view:clear 2>/dev/null || true

echo ">> Test connexion MySQL..."
if ! php artisan migrate --force --no-interaction; then
    echo "ERREUR: impossible de migrer la base. Verifie DB_URL ou DB_HOST/DB_* et MySQL Online."
    exit 1
fi

php artisan storage:link 2>/dev/null || true

if [ -n "$ADMIN_EMAIL" ]; then
    echo ">> Compte administrateur..."
    php artisan blog:ensure-admin "$ADMIN_EMAIL" --password="${ADMIN_PASSWORD:-password}" --name="${ADMIN_NAME:-Charbel}"
fi

echo ">> Migrations OK, serveur pret."
exec php artisan serve --host=0.0.0.0 --port="$PORT"
