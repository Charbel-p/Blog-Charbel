<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    public const CREATED_AT = 'cree_le';

    public const UPDATED_AT = 'modifie_le';

    protected $fillable = [
        'nom',
        'libelle_url',
    ];

    protected function name(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->nom,
            set: fn (string $value) => ['nom' => $value],
        );
    }

    protected function slug(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->libelle_url,
            set: fn (string $value) => ['libelle_url' => $value],
        );
    }

    public function posts(): HasMany
    {
        return $this->hasMany(Post::class, 'categorie_id');
    }
}
