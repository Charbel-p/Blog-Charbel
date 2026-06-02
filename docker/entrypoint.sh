#!/bin/sh
PORT="${PORT:-8080}"

echo "=== Demarrage application (port $PORT) ==="

if [ -z "$APP_KEY" ]; then
    echo "ERREUR: APP_KEY est vide. Ajoute APP_KEY dans les variables Railway."
    echo "Genere une cle en local: php artisan key:generate --show"
    exit 1
fi

php artisan config:clear 2>/dev/null || true
php artisan view:clear 2>/dev/null || true

echo ">> Migrations..."
php artisan migrate --force --no-interaction 2>&1 || echo "ATTENTION: migration echouee (verifie MySQL / DB_URL)"

php artisan storage:link 2>/dev/null || true
php artisan blog:ensure-categories 2>/dev/null || true

if [ -n "$ADMIN_EMAIL" ]; then
    php artisan blog:ensure-admin "$ADMIN_EMAIL" --password="${ADMIN_PASSWORD:-password}" --name="${ADMIN_NAME:-Admin}" 2>/dev/null || true
fi

echo ">> Serveur web pret."
exec php artisan serve --host=0.0.0.0 --port="$PORT"
