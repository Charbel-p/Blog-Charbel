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
        $user = User::factory()->create([
            'name' => 'Charbel',
            'email' => 'test@example.com',
            'is_admin' => true,
        ]);

        $category = Category::create([
            'name' => 'General',
            'slug' => 'general',
        ]);

        Post::create([
            'user_id' => $user->id,
            'category_id' => $category->id,
            'title' => 'Bienvenue sur mon blog',
            'slug' => Str::slug('Bienvenue sur mon blog'),
            'excerpt' => 'Premier article de demonstration.',
            'content' => 'Ceci est un article de demonstration pour valider le module blog, les commentaires et les notes.',
            'is_published' => true,
            'published_at' => now(),
        ]);
    }
}
