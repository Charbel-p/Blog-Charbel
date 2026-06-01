#!/bin/sh
PORT="${PORT:-8080}"

echo "=== Blog Charbel - demarrage port $PORT ==="

php artisan config:clear 2>/dev/null || true
php artisan view:clear 2>/dev/null || true

if [ ! -f public/build/manifest.json ]; then
    echo "ERREUR: public/build/manifest.json absent. Le build Vite a echoue."
    ls -la public/build 2>/dev/null || echo "(dossier public/build introuvable)"
    exit 1
fi

echo ">> Test connexion MySQL..."
if ! php artisan migrate --force --no-interaction; then
    echo "ERREUR: impossible de migrer la base. Verifie DB_URL ou DB_HOST/DB_* et MySQL Online."
    exit 1
fi

php artisan storage:link 2>/dev/null || true

echo ">> Migrations OK, serveur pret."
exec php artisan serve --host=0.0.0.0 --port="$PORT"
