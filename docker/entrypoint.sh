#!/bin/sh
PORT="${PORT:-8080}"

echo "=== Demarrage (PORT=$PORT) ==="

if [ -z "$APP_KEY" ]; then
    echo "ERREUR: APP_KEY manquant dans les variables Railway."
    exit 1
fi

php artisan config:clear 2>/dev/null || true

# Demarrer le serveur tout de suite (Railway doit voir le port ouvert)
# artisan serve sert correctement public/build (CSS, JS) — pas php -S avec index.php
echo ">> Serveur Laravel sur 0.0.0.0:$PORT ..."
php artisan serve --host=0.0.0.0 --port="$PORT" &
SERVER_PID=$!
sleep 3

echo ">> Migrations..."
php artisan migrate --force --no-interaction 2>&1 || echo "ATTENTION: migration echouee"

php artisan storage:link 2>/dev/null || true
php artisan blog:ensure-categories 2>/dev/null || true

if [ -n "$ADMIN_EMAIL" ]; then
    php artisan blog:ensure-admin "$ADMIN_EMAIL" --password="${ADMIN_PASSWORD:-password}" --name="${ADMIN_NAME:-Admin}" 2>/dev/null || true
fi

echo ">> Pret. CSS/JS dans public/build/"
wait $SERVER_PID
