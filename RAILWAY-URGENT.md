# Railway — si ca echoue encore

## PROBLEME N°1 (le plus frequent)

Sur le schema Railway, tu dois voir **2 cartes** :

1. **blog-charbel** (GitHub) = l'app
2. **MySQL** (icone base de donnees) = **Online** en vert

Si tu vois seulement **blog-charbel-db-volume** = ancien disque PostgreSQL, **ce n'est PAS MySQL**.

### A faire
1. Supprime **blog-charbel-db-volume** (Settings → Delete) si possible
2. **+ New** → **Database** → **Add MySQL**
3. Attends **Online**

---

## PROBLEME N°2 — Variables sur blog-charbel

Service **blog-charbel** → Variables → RAW Editor :

```env
DB_CONNECTION=mysql
DB_URL=${{MySQL.MYSQL_URL}}
```

+ toutes les variables APP_* (APP_KEY, APP_URL, etc.)

Si ta carte MySQL ne s'appelle pas `MySQL`, change :
`DB_URL=${{TON-NOM-MYSQL.MYSQL_URL}}`

---

## PROBLEME N°3 — Healthcheck

Le healthcheck est **desactive** dans railway.toml (plus de blocage).

Apres redeploy, regarde **Deploy Logs** (pas Build Logs).

---

## Test

Ouvre : https://blog-charbel-production.up.railway.app

- Page blanche / erreur 500 = app tourne mais BDD pas OK → revoir MySQL + DB_URL
- Site OK = tout est bon → `railway run php artisan db:seed --force`
