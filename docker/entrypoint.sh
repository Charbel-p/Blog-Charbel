#!/bin/sh
PORT="${PORT:-8080}"

echo "=== Blog Charbel - demarrage port $PORT ==="
php artisan config:clear 2>/dev/null || true

php artisan migrate --force --no-interaction 2>&1 || echo "WARN: migrate a echoue (MySQL absent ou variables DB incorrectes)"

php artisan storage:link 2>/dev/null || true

exec php artisan serve --host=0.0.0.0 --port="$PORT"
