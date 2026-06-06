<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $user = User::updateOrCreate(
            ['courriel' => env('ADMIN_EMAIL', 'test@example.com')],
            [
                'nom' => env('ADMIN_NAME', 'Charbel'),
                'mot_de_passe' => env('ADMIN_PASSWORD', 'password'),
                'est_administrateur' => true,
            ]
        );

        $this->call(CategorySeeder::class);

        $category = Category::where('libelle_url', 'general')->first();

        Post::updateOrCreate(
            ['libelle_url' => 'bienvenue-sur-mon-blog'],
            [
                'utilisateur_id' => $user->id,
                'categorie_id' => $category->id,
                'titre' => 'Bienvenue sur mon blog',
                'resume' => 'Premier article de demonstration.',
                'contenu' => 'Ceci est un article de demonstration pour valider le module blog, les commentaires et les notes.',
                'est_publie' => true,
                'publie_le' => now(),
            ]
        );
    }
}
