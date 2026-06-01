<?php

namespace App\Console\Commands;

use App\Models\Category;
use Illuminate\Console\Command;

class EnsureCategoriesCommand extends Command
{
    protected $signature = 'blog:ensure-categories';

    protected $description = 'Cree les categories par defaut du blog si elles n\'existent pas';

    public function handle(): int
    {
        $categories = [
            ['name' => 'General', 'slug' => 'general'],
            ['name' => 'Voyage', 'slug' => 'voyage'],
            ['name' => 'Reflexions', 'slug' => 'reflexions'],
        ];

        foreach ($categories as $data) {
            $category = Category::updateOrCreate(
                ['slug' => $data['slug']],
                ['name' => $data['name']]
            );

            $this->line("  - {$category->name}");
        }

        $this->info('Categories pretes ('.count($categories).' au total).');

        return self::SUCCESS;
    }
}
