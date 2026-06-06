<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('utilisateurs')) {
            return;
        }

        Schema::disableForeignKeyConstraints();

        $this->dropBlogForeignKeys();

        Schema::rename('users', 'utilisateurs');
        Schema::rename('posts', 'articles');
        Schema::rename('comments', 'commentaires');
        Schema::rename('reviews', 'avis');

        DB::statement('ALTER TABLE utilisateurs CHANGE name nom VARCHAR(255) NOT NULL');
        DB::statement('ALTER TABLE utilisateurs CHANGE email courriel VARCHAR(255) NOT NULL');
        DB::statement('ALTER TABLE utilisateurs CHANGE email_verified_at courriel_verifie_le TIMESTAMP NULL DEFAULT NULL');
        DB::statement('ALTER TABLE utilisateurs CHANGE is_admin est_administrateur TINYINT(1) NOT NULL DEFAULT 0');
        DB::statement('ALTER TABLE utilisateurs CHANGE password mot_de_passe VARCHAR(255) NOT NULL');
        DB::statement('ALTER TABLE utilisateurs CHANGE remember_token jeton_souvenir VARCHAR(100) NULL DEFAULT NULL');
        DB::statement('ALTER TABLE utilisateurs CHANGE created_at cree_le TIMESTAMP NULL DEFAULT NULL');
        DB::statement('ALTER TABLE utilisateurs CHANGE updated_at modifie_le TIMESTAMP NULL DEFAULT NULL');

        DB::statement('ALTER TABLE categories CHANGE name nom VARCHAR(255) NOT NULL');
        DB::statement('ALTER TABLE categories CHANGE slug libelle_url VARCHAR(255) NOT NULL');
        DB::statement('ALTER TABLE categories CHANGE created_at cree_le TIMESTAMP NULL DEFAULT NULL');
        DB::statement('ALTER TABLE categories CHANGE updated_at modifie_le TIMESTAMP NULL DEFAULT NULL');

        DB::statement('ALTER TABLE articles CHANGE user_id utilisateur_id BIGINT UNSIGNED NOT NULL');
        DB::statement('ALTER TABLE articles CHANGE category_id categorie_id BIGINT UNSIGNED NOT NULL');
        DB::statement('ALTER TABLE articles CHANGE title titre VARCHAR(255) NOT NULL');
        DB::statement('ALTER TABLE articles CHANGE slug libelle_url VARCHAR(255) NOT NULL');
        DB::statement('ALTER TABLE articles CHANGE excerpt resume TEXT NULL DEFAULT NULL');
        DB::statement('ALTER TABLE articles CHANGE cover_image image_couverture VARCHAR(255) NULL DEFAULT NULL');
        DB::statement('ALTER TABLE articles CHANGE content contenu LONGTEXT NOT NULL');
        DB::statement('ALTER TABLE articles CHANGE is_published est_publie TINYINT(1) NOT NULL DEFAULT 0');
        DB::statement('ALTER TABLE articles CHANGE published_at publie_le TIMESTAMP NULL DEFAULT NULL');
        DB::statement('ALTER TABLE articles CHANGE created_at cree_le TIMESTAMP NULL DEFAULT NULL');
        DB::statement('ALTER TABLE articles CHANGE updated_at modifie_le TIMESTAMP NULL DEFAULT NULL');

        DB::statement('ALTER TABLE commentaires CHANGE post_id article_id BIGINT UNSIGNED NOT NULL');
        DB::statement('ALTER TABLE commentaires CHANGE user_id utilisateur_id BIGINT UNSIGNED NOT NULL');
        DB::statement('ALTER TABLE commentaires CHANGE parent_id commentaire_parent_id BIGINT UNSIGNED NULL DEFAULT NULL');
        DB::statement('ALTER TABLE commentaires CHANGE content contenu TEXT NOT NULL');
        DB::statement('ALTER TABLE commentaires CHANGE is_approved est_approuve TINYINT(1) NOT NULL DEFAULT 0');
        DB::statement('ALTER TABLE commentaires CHANGE created_at cree_le TIMESTAMP NULL DEFAULT NULL');
        DB::statement('ALTER TABLE commentaires CHANGE updated_at modifie_le TIMESTAMP NULL DEFAULT NULL');

        DB::statement('ALTER TABLE avis CHANGE post_id article_id BIGINT UNSIGNED NOT NULL');
        DB::statement('ALTER TABLE avis CHANGE user_id utilisateur_id BIGINT UNSIGNED NOT NULL');
        DB::statement('ALTER TABLE avis CHANGE rating note TINYINT UNSIGNED NOT NULL');
        DB::statement('ALTER TABLE avis CHANGE opinion texte_avis VARCHAR(500) NULL DEFAULT NULL');
        DB::statement('ALTER TABLE avis CHANGE created_at cree_le TIMESTAMP NULL DEFAULT NULL');
        DB::statement('ALTER TABLE avis CHANGE updated_at modifie_le TIMESTAMP NULL DEFAULT NULL');

        $this->restoreBlogForeignKeys();

        Schema::enableForeignKeyConstraints();
    }

    public function down(): void
    {
        if (! Schema::hasTable('utilisateurs')) {
            return;
        }

        Schema::disableForeignKeyConstraints();

        $this->dropBlogForeignKeys();

        DB::statement('ALTER TABLE avis CHANGE modifie_le updated_at TIMESTAMP NULL DEFAULT NULL');
        DB::statement('ALTER TABLE avis CHANGE cree_le created_at TIMESTAMP NULL DEFAULT NULL');
        DB::statement('ALTER TABLE avis CHANGE texte_avis opinion VARCHAR(500) NULL DEFAULT NULL');
        DB::statement('ALTER TABLE avis CHANGE note rating TINYINT UNSIGNED NOT NULL');
        DB::statement('ALTER TABLE avis CHANGE utilisateur_id user_id BIGINT UNSIGNED NOT NULL');
        DB::statement('ALTER TABLE avis CHANGE article_id post_id BIGINT UNSIGNED NOT NULL');

        DB::statement('ALTER TABLE commentaires CHANGE modifie_le updated_at TIMESTAMP NULL DEFAULT NULL');
        DB::statement('ALTER TABLE commentaires CHANGE cree_le created_at TIMESTAMP NULL DEFAULT NULL');
        DB::statement('ALTER TABLE commentaires CHANGE est_approuve is_approved TINYINT(1) NOT NULL DEFAULT 0');
        DB::statement('ALTER TABLE commentaires CHANGE contenu content TEXT NOT NULL');
        DB::statement('ALTER TABLE commentaires CHANGE commentaire_parent_id parent_id BIGINT UNSIGNED NULL DEFAULT NULL');
        DB::statement('ALTER TABLE commentaires CHANGE utilisateur_id user_id BIGINT UNSIGNED NOT NULL');
        DB::statement('ALTER TABLE commentaires CHANGE article_id post_id BIGINT UNSIGNED NOT NULL');

        DB::statement('ALTER TABLE articles CHANGE modifie_le updated_at TIMESTAMP NULL DEFAULT NULL');
        DB::statement('ALTER TABLE articles CHANGE cree_le created_at TIMESTAMP NULL DEFAULT NULL');
        DB::statement('ALTER TABLE articles CHANGE publie_le published_at TIMESTAMP NULL DEFAULT NULL');
        DB::statement('ALTER TABLE articles CHANGE est_publie is_published TINYINT(1) NOT NULL DEFAULT 0');
        DB::statement('ALTER TABLE articles CHANGE contenu content LONGTEXT NOT NULL');
        DB::statement('ALTER TABLE articles CHANGE image_couverture cover_image VARCHAR(255) NULL DEFAULT NULL');
        DB::statement('ALTER TABLE articles CHANGE resume excerpt TEXT NULL DEFAULT NULL');
        DB::statement('ALTER TABLE articles CHANGE libelle_url slug VARCHAR(255) NOT NULL');
        DB::statement('ALTER TABLE articles CHANGE titre title VARCHAR(255) NOT NULL');
        DB::statement('ALTER TABLE articles CHANGE categorie_id category_id BIGINT UNSIGNED NOT NULL');
        DB::statement('ALTER TABLE articles CHANGE utilisateur_id user_id BIGINT UNSIGNED NOT NULL');

        DB::statement('ALTER TABLE categories CHANGE modifie_le updated_at TIMESTAMP NULL DEFAULT NULL');
        DB::statement('ALTER TABLE categories CHANGE cree_le created_at TIMESTAMP NULL DEFAULT NULL');
        DB::statement('ALTER TABLE categories CHANGE libelle_url slug VARCHAR(255) NOT NULL');
        DB::statement('ALTER TABLE categories CHANGE nom name VARCHAR(255) NOT NULL');

        DB::statement('ALTER TABLE utilisateurs CHANGE modifie_le updated_at TIMESTAMP NULL DEFAULT NULL');
        DB::statement('ALTER TABLE utilisateurs CHANGE cree_le created_at TIMESTAMP NULL DEFAULT NULL');
        DB::statement('ALTER TABLE utilisateurs CHANGE jeton_souvenir remember_token VARCHAR(100) NULL DEFAULT NULL');
        DB::statement('ALTER TABLE utilisateurs CHANGE mot_de_passe password VARCHAR(255) NOT NULL');
        DB::statement('ALTER TABLE utilisateurs CHANGE est_administrateur is_admin TINYINT(1) NOT NULL DEFAULT 0');
        DB::statement('ALTER TABLE utilisateurs CHANGE courriel_verifie_le email_verified_at TIMESTAMP NULL DEFAULT NULL');
        DB::statement('ALTER TABLE utilisateurs CHANGE courriel email VARCHAR(255) NOT NULL');
        DB::statement('ALTER TABLE utilisateurs CHANGE nom name VARCHAR(255) NOT NULL');

        Schema::rename('avis', 'reviews');
        Schema::rename('commentaires', 'comments');
        Schema::rename('articles', 'posts');
        Schema::rename('utilisateurs', 'users');

        $this->restoreEnglishForeignKeys();

        Schema::enableForeignKeyConstraints();
    }

    private function dropBlogForeignKeys(): void
    {
        if (Schema::hasTable('commentaires') || Schema::hasTable('comments')) {
            $comments = Schema::hasTable('commentaires') ? 'commentaires' : 'comments';
            $this->dropForeignKeyIfExists($comments, "{$comments}_post_id_foreign");
            $this->dropForeignKeyIfExists($comments, "{$comments}_user_id_foreign");
            $this->dropForeignKeyIfExists($comments, "{$comments}_parent_id_foreign");
        }

        if (Schema::hasTable('articles') || Schema::hasTable('posts')) {
            $posts = Schema::hasTable('articles') ? 'articles' : 'posts';
            $this->dropForeignKeyIfExists($posts, "{$posts}_user_id_foreign");
            $this->dropForeignKeyIfExists($posts, "{$posts}_category_id_foreign");
        }

        if (Schema::hasTable('avis') || Schema::hasTable('reviews')) {
            $reviews = Schema::hasTable('avis') ? 'avis' : 'reviews';
            $this->dropForeignKeyIfExists($reviews, "{$reviews}_post_id_foreign");
            $this->dropForeignKeyIfExists($reviews, "{$reviews}_user_id_foreign");
            $this->dropIndexIfExists($reviews, "{$reviews}_post_id_user_id_unique");
        }

        if (Schema::hasTable('sessions')) {
            $this->dropForeignKeyIfExists('sessions', 'sessions_user_id_foreign');
        }
    }

    private function restoreBlogForeignKeys(): void
    {
        DB::statement('ALTER TABLE articles ADD CONSTRAINT articles_utilisateur_id_foreign FOREIGN KEY (utilisateur_id) REFERENCES utilisateurs(id) ON DELETE CASCADE');
        DB::statement('ALTER TABLE articles ADD CONSTRAINT articles_categorie_id_foreign FOREIGN KEY (categorie_id) REFERENCES categories(id) ON DELETE CASCADE');
        DB::statement('ALTER TABLE commentaires ADD CONSTRAINT commentaires_article_id_foreign FOREIGN KEY (article_id) REFERENCES articles(id) ON DELETE CASCADE');
        DB::statement('ALTER TABLE commentaires ADD CONSTRAINT commentaires_utilisateur_id_foreign FOREIGN KEY (utilisateur_id) REFERENCES utilisateurs(id) ON DELETE CASCADE');
        DB::statement('ALTER TABLE commentaires ADD CONSTRAINT commentaires_commentaire_parent_id_foreign FOREIGN KEY (commentaire_parent_id) REFERENCES commentaires(id) ON DELETE CASCADE');
        DB::statement('ALTER TABLE avis ADD CONSTRAINT avis_article_id_foreign FOREIGN KEY (article_id) REFERENCES articles(id) ON DELETE CASCADE');
        DB::statement('ALTER TABLE avis ADD CONSTRAINT avis_utilisateur_id_foreign FOREIGN KEY (utilisateur_id) REFERENCES utilisateurs(id) ON DELETE CASCADE');
        DB::statement('ALTER TABLE avis ADD UNIQUE avis_article_id_utilisateur_id_unique (article_id, utilisateur_id)');

        if (Schema::hasTable('sessions')) {
            DB::statement('ALTER TABLE sessions ADD CONSTRAINT sessions_user_id_foreign FOREIGN KEY (user_id) REFERENCES utilisateurs(id) ON DELETE SET NULL');
        }
    }

    private function restoreEnglishForeignKeys(): void
    {
        DB::statement('ALTER TABLE posts ADD CONSTRAINT posts_user_id_foreign FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE');
        DB::statement('ALTER TABLE posts ADD CONSTRAINT posts_category_id_foreign FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE CASCADE');
        DB::statement('ALTER TABLE comments ADD CONSTRAINT comments_post_id_foreign FOREIGN KEY (post_id) REFERENCES posts(id) ON DELETE CASCADE');
        DB::statement('ALTER TABLE comments ADD CONSTRAINT comments_user_id_foreign FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE');
        DB::statement('ALTER TABLE comments ADD CONSTRAINT comments_parent_id_foreign FOREIGN KEY (parent_id) REFERENCES comments(id) ON DELETE CASCADE');
        DB::statement('ALTER TABLE reviews ADD CONSTRAINT reviews_post_id_foreign FOREIGN KEY (post_id) REFERENCES posts(id) ON DELETE CASCADE');
        DB::statement('ALTER TABLE reviews ADD CONSTRAINT reviews_user_id_foreign FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE');
        DB::statement('ALTER TABLE reviews ADD UNIQUE reviews_post_id_user_id_unique (post_id, user_id)');

        if (Schema::hasTable('sessions')) {
            DB::statement('ALTER TABLE sessions ADD CONSTRAINT sessions_user_id_foreign FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL');
        }
    }

    private function dropForeignKeyIfExists(string $table, string $foreignKey): void
    {
        $exists = DB::selectOne(
            'SELECT CONSTRAINT_NAME FROM information_schema.TABLE_CONSTRAINTS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = ? AND CONSTRAINT_NAME = ? AND CONSTRAINT_TYPE = ?',
            [$table, $foreignKey, 'FOREIGN KEY']
        );

        if ($exists) {
            DB::statement("ALTER TABLE {$table} DROP FOREIGN KEY {$foreignKey}");
        }
    }

    private function dropIndexIfExists(string $table, string $index): void
    {
        $exists = DB::selectOne(
            'SELECT INDEX_NAME FROM information_schema.STATISTICS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = ? AND INDEX_NAME = ? LIMIT 1',
            [$table, $index]
        );

        if ($exists) {
            DB::statement("ALTER TABLE {$table} DROP INDEX {$index}");
        }
    }
};
