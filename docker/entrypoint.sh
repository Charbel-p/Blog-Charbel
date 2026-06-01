#!/bin/sh

PORT="${PORT:-8080}"
echo "=== Demarrage Le blog de Charbel (port $PORT) ==="

php artisan config:clear 2>/dev/null || true

# Demarrer le serveur tout de suite (healthcheck Railway)
php artisan serve --host=0.0.0.0 --port="$PORT" &
SERVER_PID=$!
sleep 2

# Attendre MySQL puis migrer (plusieurs essais)
MIGRATED=0
for i in 1 2 3 4 5 6 7 8 9 10 11 12 13 14 15; do
    if php artisan migrate --force --no-interaction 2>&1; then
        echo ">> Migrations OK"
        MIGRATED=1
        break
    fi
    echo ">> Attente MySQL... essai $i/15"
    sleep 4
done

if [ "$MIGRATED" -eq 0 ]; then
    echo ">> ATTENTION: migrations echouees. Verifie DB_* et que MySQL est Online."
fi

php artisan storage:link 2>/dev/null || true

wait $SERVER_PID
