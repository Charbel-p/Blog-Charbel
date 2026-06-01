# Railway — MySQL (comme en local)

Le blog utilise **MySQL** (comme XAMPP / phpMyAdmin), **pas PostgreSQL**.

---

## 1. Sur Railway : une base MySQL, pas PostgreSQL

1. Si tu as un service **`blog-charbel-db`** en **PostgreSQL** (icone elephant) :
   - Clique dessus → **Settings** → **Remove service** (ou supprime-le)
2. Dans le projet : **+ New** → **Database** → **Add MySQL**
3. Railway cree la base **automatiquement** — rien a faire dans phpMyAdmin

---

## 2. Variables (RAW Editor) — service `blog-charbel`

Remplace **tout** par ce bloc.  
Adapte le nom du service MySQL si le tien ne s'appelle pas `MySQL` (ex. `mysql`, `blog-charbel-mysql`).

### Methode A — references Railway (recommandee)

Clique **Add variable reference** vers ton service MySQL, ou colle :

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
DB_CONNECTION="mysql"
DB_HOST="${{MySQL.MYSQLHOST}}"
DB_PORT="${{MySQL.MYSQLPORT}}"
DB_DATABASE="${{MySQL.MYSQLDATABASE}}"
DB_USERNAME="${{MySQL.MYSQLUSER}}"
DB_PASSWORD="${{MySQL.MYSQLPASSWORD}}"
```

> Si ton service MySQL s'appelle autrement (ex. `blog-charbel-mysql`), remplace `MySQL` par ce nom :
> `${{blog-charbel-mysql.MYSQLHOST}}`, etc.

### Methode B — une seule URL (si Railway propose MYSQL_URL)

```env
DB_CONNECTION="mysql"
DB_URL="${{MySQL.MYSQL_URL}}"
```

(+ les variables APP_* comme ci-dessus)

---

## 3. Builder et redeploy

1. Service **`blog-charbel`** → **Settings** → **Build** → **Nixpacks**
2. **Update Variables** puis **Redeploy**

Les tables sont creees par `php artisan migrate` au demarrage.

---

## 4. Compte admin (une fois le deploy vert)

```bash
railway run php artisan db:seed --force
```

Ou via le shell Railway sur le service app.

- Email : `test@example.com`
- Mot de passe : `password`

---

## Rappel

| En local | Sur Railway |
|----------|-------------|
| XAMPP MySQL + phpMyAdmin | Service **MySQL** Railway |
| Base `blog_charbel` | Creee automatiquement |
| `php artisan migrate` | Au demarrage de l'app |

**PostgreSQL** venait du fichier `render.yaml` (option Render). Pour Railway, utilise **uniquement MySQL**.
