# Déployer « Le blog de Charbel » gratuitement en ligne

Ce guide explique comment mettre le blog en ligne **sans payer**, en utilisant ton dépôt GitHub :  
**https://github.com/Charbel-p/Blog-Charbel**

> Le fichier `.env` ne va **jamais** sur GitHub. Tu le recrées sur la plateforme d’hébergement (variables d’environnement).

---

## Option recommandée : Railway (MySQL, comme en local)

**Railway** : [https://railway.app](https://railway.app)  
- Gratuit avec **crédits mensuels** (~5 $) — suffisant pour un petit blog  
- Connexion avec **GitHub**  
- **MySQL** inclus (pas besoin de changer ta base)

### Étape 1 — Créer un compte

1. Va sur [railway.app](https://railway.app)  
2. **Login with GitHub**  
3. Autorise Railway à accéder à tes dépôts  

### Étape 2 — Nouveau projet

1. **New Project** → **Deploy from GitHub repo**  
2. Choisis **`Charbel-p/Blog-Charbel`**  
3. Railway détecte Laravel et utilise `nixpacks.toml`  

### Étape 3 — Ajouter MySQL

1. Dans le projet : **+ New** → **Database** → **MySQL**  
2. Clique sur le service **MySQL** → onglet **Variables**  
3. Clique sur le service **Blog-Charbel** (ton app) → **Variables** → **Add variable references** (ou « Raw Editor »)  

Ajoute ces variables en **référençant** le service MySQL :

| Variable | Valeur (référence Railway) |
|----------|----------------------------|
| `DB_CONNECTION` | `mysql` |
| `DB_HOST` | `${{MySQL.MYSQLHOST}}` |
| `DB_PORT` | `${{MySQL.MYSQLPORT}}` |
| `DB_DATABASE` | `${{MySQL.MYSQLDATABASE}}` |
| `DB_USERNAME` | `${{MySQL.MYSQLUSER}}` |
| `DB_PASSWORD` | `${{MySQL.MYSQLPASSWORD}}` |

*(Les noms exacts peuvent être `MySQL` ou le nom que tu as donné au service — Railway propose le menu « Add reference ».)*

### Étape 4 — Variables Laravel obligatoires

Toujours sur le service **app** :

| Variable | Valeur |
|----------|--------|
| `APP_NAME` | `Le blog de Charbel` |
| `APP_ENV` | `production` |
| `APP_DEBUG` | `false` |
| `APP_KEY` | Génère avec `php artisan key:generate --show` en local, copie la clé |
| `APP_URL` | L’URL publique Railway (ex. `https://blog-charbel-production.up.railway.app`) |
| `LOG_CHANNEL` | `stderr` |
| `SESSION_DRIVER` | `database` |
| `CACHE_STORE` | `database` |
| `QUEUE_CONNECTION` | `database` |
| `FILESYSTEM_DISK` | `local` |

**Générer `APP_KEY` en local :**
```powershell
cd blog-personnel
php artisan key:generate --show
```
Copie la ligne `base64:...` dans Railway.

### Étape 5 — Domaine public

1. Service app → **Settings** → **Networking** → **Generate Domain**  
2. Copie l’URL et mets-la dans `APP_URL`  
3. **Redeploy** si tu as changé `APP_URL`  

### Étape 6 — Peupler la base (une fois)

Dans Railway : service app → **Settings** → onglet où tu peux lancer une commande, ou installe **Railway CLI** :

```bash
railway run php artisan db:seed --force
```

Compte admin après seed : `test@example.com` / `password` — **change le mot de passe** ensuite.

### Étape 7 — Vérifier

Ouvre l’URL Railway. Si erreur 500, consulte **Deployments** → **View logs**.

---

## Option 2 : Render (100 % gratuit, PostgreSQL)

**Render** : [https://render.com](https://render.com)  
- Plan **Free** (pas de carte pour le web + base gratuite dans certains cas)  
- Le site **se met en veille** après ~15 min sans visite (réveil lent au 1er clic)  
- Base **PostgreSQL** (pas MySQL) — Laravel gère les deux avec les mêmes migrations  

1. Compte Render + lier GitHub  
2. **New** → **Blueprint** → repo `Blog-Charbel` (fichier `render.yaml` inclus)  
3. Ou **New Web Service** → Docker, repo GitHub  
4. Créer une base **PostgreSQL Free** et lier `DATABASE_URL`  
5. Variables : `APP_KEY`, `APP_URL`, `APP_ENV=production`, `APP_DEBUG=false`  

Render injecte souvent `DATABASE_URL` ; Laravel 12 peut l’utiliser si `DB_CONNECTION=pgsql` est défini dans `render.yaml`.

---

## Limites du gratuit (important)

| Sujet | Détail |
|-------|--------|
| **Images uploadées** | Sur Railway/Render, le disque est **éphémère** : les photos d’articles peuvent **disparaître** après un redéploiement. Pour un vrai blog en prod, il faudrait plus tard un stockage type S3 / Cloudinary. |
| **Performances** | Render Free = mise en veille ; premier chargement lent. |
| **Crédits Railway** | Si les crédits sont épuisés, le service s’arrête jusqu’au mois suivant. |
| **Email** | Pas d’envoi d’emails réels sans service mail (Mailtrap, Resend, etc.). |

---

## Checklist avant de déployer

- [ ] Code poussé sur GitHub (`git push`)  
- [ ] `APP_DEBUG=false` en production  
- [ ] `APP_KEY` défini sur l’hébergeur  
- [ ] `APP_URL` = URL réelle du site (avec `https://`)  
- [ ] Base de données liée (MySQL Railway ou PostgreSQL Render)  
- [ ] `php artisan db:seed` une fois pour l’admin (optionnel)  

---

## Mettre à jour le site après des modifications

```powershell
cd blog-personnel
git add .
git commit -m "Description de tes changements"
git push
```

Railway / Render redéploient automatiquement depuis GitHub.

---

## Besoin d’aide ?

En cas d’erreur, envoie une capture des **logs de déploiement** (pas ton fichier `.env`).
