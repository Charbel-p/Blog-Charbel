# Checklist Railway — « L'application n'a pas repondu »

Guide pour deployer le blog (ami ou toi). URL type : `xxx-production.up.railway.app`

---

## 1. Services obligatoires sur Railway

Le projet doit avoir **2 cartes** :

| Service | Role |
|---------|------|
| **App** (GitHub) | Le code Laravel |
| **MySQL** | Base de donnees (**Online** en vert) |

Pas seulement un « volume » : il faut un vrai service **MySQL**.

---

## 2. Variables sur le service APP (RAW Editor)

Copier-coller et **adapter** :

```env
APP_NAME="Blog personnel"
APP_ENV="production"
APP_DEBUG="true"
APP_KEY="COLLER_ICI_LA_CLE_BASE64"
APP_URL="https://TON-URL.up.railway.app"
LOG_CHANNEL="stderr"
SESSION_DRIVER="file"
CACHE_STORE="file"
QUEUE_CONNECTION="sync"
FILESYSTEM_DISK="local"
DB_CONNECTION="mysql"
DB_URL="${{MySQL.MYSQL_URL}}"
ADMIN_EMAIL="email-de-ton-ami@example.com"
ADMIN_PASSWORD="mot-de-passe-securise"
ADMIN_NAME="Prenom"
```

### APP_KEY (obligatoire)

Sur un PC avec le projet :

```bash
php artisan key:generate --show
```

Copier la ligne `base64:...` dans `APP_KEY`.

**Ne pas** prendre APP_KEY dans MySQL : ce n'est pas la base de donnees.

### DB_URL

Remplacer `MySQL` par le **nom exact** de la carte MySQL sur Railway  
(ex. `${{blog-personnel-mysql.MYSQL_URL}}`).

### APP_URL

= l'URL Railway de l'app (Settings → Networking → Generate Domain).

---

## 3. Ordre des operations

1. Creer projet Railway + lier GitHub
2. Ajouter **MySQL** → attendre **Online**
3. Configurer variables sur l'**app**
4. **Redeploy**
5. Ouvrir `https://TON-URL/debug-deploy` → doit afficher `"database":"connected"`
6. Ouvrir `https://TON-URL/sync-categories` → doit afficher 3 categories
7. Login avec `ADMIN_EMAIL` / `ADMIN_PASSWORD`

---

## 4. Si « L'application n'a pas repondu »

1. Service **app** → **Deployments** → **Deploy Logs** (pas Build Logs)
2. Chercher :
   - `ERREUR: APP_KEY est vide` → ajouter APP_KEY
   - `migration echouee` → verifier MySQL + DB_URL
   - autre message PHP → copier et corriger

3. Verifier **Build Logs** = Success (vert)

4. **Redeploy** apres chaque changement de variables

---

## 5. Photos (images articles)

Sur Railway gratuit, les images uploadées peuvent disparaitre apres un redeploy.  
Re-uploader la photo apres chaque deploy si besoin.

---

## 6. Commandes utiles (si Railway CLI installe)

```bash
railway login
railway link
railway run php artisan blog:ensure-admin email@example.com --password=secret
railway run php artisan blog:ensure-categories
```
