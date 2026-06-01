# Le blog de Charbel

Blog personnel développé avec **Laravel 12**, **MySQL** (phpMyAdmin / XAMPP), **Breeze** et **Tailwind CSS**.

## Fonctionnalités

- Articles publiés avec catégories et image de couverture
- Commentaires et notes (1 à 5) pour les visiteurs inscrits
- Espace admin : CRUD articles, modération des commentaires
- Dashboard visiteur : historique de ses commentaires

## Prérequis

- PHP 8.2+
- Composer
- Node.js et npm
- XAMPP (Apache + MySQL)

## Installation

```bash
# Cloner le projet
git clone https://github.com/VOTRE_USERNAME/blog-charbel.git
cd blog-charbel

# Dépendances
composer install
npm install
npm run build

# Configuration
cp .env.example .env
php artisan key:generate
```

Configurer `.env` (MySQL) :

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=blog_charbel
DB_USERNAME=root
DB_PASSWORD=
```

Créer la base `blog_charbel` dans phpMyAdmin, puis :

```bash
php artisan migrate --seed
php artisan storage:link
php artisan serve --port=8002
```

Site : http://127.0.0.1:8002

## Compte admin (après seed)

- Email : `test@example.com`
- Mot de passe : `password`

## Relancer après redémarrage du PC

1. XAMPP → démarrer **Apache** et **MySQL**
2. `php artisan serve --port=8002`

## Déployer en ligne (gratuit)

Guide complet : **[DEPLOIEMENT.md](DEPLOIEMENT.md)**

- **Railway** (recommandé) : MySQL + déploiement depuis GitHub  
- **Render** : gratuit, PostgreSQL, mise en veille après inactivité  

Dépôt GitHub : https://github.com/Charbel-p/Blog-Charbel
