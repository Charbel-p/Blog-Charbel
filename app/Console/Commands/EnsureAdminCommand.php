<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class EnsureAdminCommand extends Command
{
    protected $signature = 'blog:ensure-admin
                            {email? : Email de l\'administrateur}
                            {--password= : Mot de passe (defaut: ADMIN_PASSWORD ou "password")}
                            {--name= : Nom affiche (defaut: ADMIN_NAME ou "Charbel")}';

    protected $description = 'Cree ou met a jour le compte administrateur du blog';

    public function handle(): int
    {
        $email = $this->argument('email') ?? env('ADMIN_EMAIL', 'test@example.com');
        $password = $this->option('password') ?? env('ADMIN_PASSWORD', 'password');
        $name = $this->option('name') ?? env('ADMIN_NAME', 'Charbel');

        $user = User::updateOrCreate(
            ['email' => $email],
            [
                'name' => $name,
                'password' => $password,
                'is_admin' => true,
            ]
        );

        $this->info("Administrateur pret : {$user->email}");
        $this->line('Connexion avec cet email et le mot de passe choisi.');

        return self::SUCCESS;
    }
}
