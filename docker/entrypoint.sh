#!/bin/sh
set -e

PORT="${PORT:-8080}"
echo "=== Demarrage (PORT=$PORT) ==="

if [ -z "$APP_KEY" ]; then
    echo "ERREUR: APP_KEY manquant dans les variables Railway."
    exit 1
fi

cd /app || cd "$(dirname "$0")/.." || true

php artisan config:clear 2>/dev/null || true

# Ouvrir le port TOUT DE SUITE (Railway coupe si rien n'ecoute)
echo ">> Demarrage serveur HTTP sur 0.0.0.0:$PORT ..."
php -S "0.0.0.0:${PORT}" -t public public/index.php &
SERVER_PID=$!
sleep 2

echo ">> Migrations et donnees initiales..."
php artisan migrate --force --no-interaction 2>&1 || echo "ATTENTION: migration echouee"
php artisan storage:link 2>/dev/null || true
php artisan blog:ensure-categories 2>/dev/null || true

if [ -n "$ADMIN_EMAIL" ]; then
    php artisan blog:ensure-admin "$ADMIN_EMAIL" --password="${ADMIN_PASSWORD:-password}" --name="${ADMIN_NAME:-Admin}" 2>/dev/null || true
fi

echo ">> Application accessible sur le port $PORT"
wait $SERVER_PID
