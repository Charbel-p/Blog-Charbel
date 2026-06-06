<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends Factory<User>
 */
class UserFactory extends Factory
{
    protected static ?string $password;

    public function definition(): array
    {
        return [
            'nom' => fake()->name(),
            'courriel' => fake()->unique()->safeEmail(),
            'courriel_verifie_le' => now(),
            'mot_de_passe' => static::$password ??= Hash::make('password'),
            'jeton_souvenir' => Str::random(10),
        ];
    }

    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'courriel_verifie_le' => null,
        ]);
    }
}
