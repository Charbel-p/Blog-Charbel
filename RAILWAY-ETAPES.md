# Railway — guide simple (base de donnees)

## Tu n'as RIEN a faire dans MySQL / phpMyAdmin sur Railway

Sur ton PC (XAMPP), tu utilisais phpMyAdmin pour creer `blog_charbel`.

**Sur Railway, c'est different :**
- Railway **cree et gere** la base tout seul
- Tu ne te connectes pas a phpMyAdmin
- Les tables sont creees par `php artisan migrate` au demarrage (fichier `docker/entrypoint.sh`)

---

## Quelle base utiliser ?

Sur ta capture Railway, tu as **`blog-charbel-db`** en **PostgreSQL** (elephant).

| Service Railway | A utiliser ? |
|-----------------|--------------|
| `blog-charbel-db` (PostgreSQL) | **OUI** — garde celui-la |
| MySQL (si tu en as cree un autre) | **NON** — supprime-le pour eviter la confusion |

### Variables sur le service `blog-charbel` (app)

```env
DB_CONNECTION="pgsql"
DB_URL="${{blog-charbel-db.DATABASE_URL}}"
```

Pas besoin de DB_HOST, DB_USER, etc. avec PostgreSQL sur Railway.

---

## Variables completes (RAW Editor)

```env
APP_NAME="Le blog de Charbel"
APP_ENV="production"
APP_DEBUG="false"
APP_KEY="base64:9nlaJm/LHj1VsXNl9Dhk5Bs+aP2gIsGTi+eoc8Uei2o="
APP_URL="https://TON-URL.up.railway.app"
LOG_CHANNEL="stderr"
SESSION_DRIVER="database"
CACHE_STORE="database"
QUEUE_CONNECTION="database"
FILESYSTEM_DISK="local"
DB_CONNECTION="pgsql"
DB_URL="${{blog-charbel-db.DATABASE_URL}}"
```

---

## Apres un deploy reussi

1. Ouvre l'URL du site
2. Pour l'admin : Railway → service app → **Shell** ou CLI :
   `railway run php artisan db:seed --force`
3. Connexion : `test@example.com` / `password`

---

## Si le build echoue encore

1. Service `blog-charbel` → **Settings** → **Builder** → choisis **Nixpacks** (pas Dockerfile)
2. **Redeploy**
3. Copie les dernieres lignes des logs ici
