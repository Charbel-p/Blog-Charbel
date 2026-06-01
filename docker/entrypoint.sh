#!/bin/sh

echo "=== Demarrage Le blog de Charbel ==="
echo "PORT=${PORT:-8080}"

php artisan config:clear 2>/dev/null || true

echo ">> Connexion base de donnees + migrations..."
if ! php artisan migrate --force --no-interaction; then
    echo ""
    echo "ERREUR MIGRATION : la base MySQL n'est pas joignable."
    echo "Verifie sur Railway :"
    echo "  1. Service MySQL ajoute et Online"
    echo "  2. Variables DB_HOST, DB_PORT, DB_DATABASE, DB_USERNAME, DB_PASSWORD"
    echo "  3. DB_CONNECTION=mysql"
    exit 1
fi

php artisan storage:link 2>/dev/null || true
php artisan config:cache 2>/dev/null || true

echo ">> Serveur web sur le port ${PORT:-8080}..."
exec php artisan serve --host=0.0.0.0 --port="${PORT:-8080}"
