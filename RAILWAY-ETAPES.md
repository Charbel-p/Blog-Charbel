# Railway — variables pour corriger l'erreur 500

Quand le site affiche **500 | SERVER ERROR**, mets **temporairement** `APP_DEBUG=true` pour voir l'erreur exacte sur la page.

## RAW Editor — service blog-charbel (copier tout)

```env
APP_NAME="Le blog de Charbel"
APP_ENV="production"
APP_DEBUG="true"
APP_KEY="base64:9nlaJm/LHj1VsXNl9Dhk5Bs+aP2gIsGTi+eoc8Uei2o="
APP_URL="https://blog-charbel-production.up.railway.app"
LOG_CHANNEL="stderr"
SESSION_DRIVER="file"
CACHE_STORE="file"
QUEUE_CONNECTION="sync"
FILESYSTEM_DISK="local"
DB_CONNECTION="mysql"
DB_URL="${{MySQL.MYSQL_URL}}"
```

> Remplace `MySQL` par le nom exact de ta carte MySQL si different.
> Quand tout marche, remets `APP_DEBUG="false"`.

**Supprime** les anciennes lignes `DB_HOST`, `DB_PORT`, etc. si tu utilises `DB_URL` (evite les conflits).

## Apres Update Variables

1. **Redeploy**
2. Recharge le site — tu verras l'erreur precise (ex. base, Vite, APP_KEY)
3. **Deploy Logs** : cherche `Migrations OK` ou `ERREUR`

## Admin (une fois le site OK)

```bash
railway run php artisan db:seed --force
```

`test@example.com` / `password`
