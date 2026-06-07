<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected $table = 'utilisateurs';

    protected $authPasswordName = 'mot_de_passe';

    protected $rememberTokenName = 'jeton_souvenir';

    public const CREATED_AT = 'cree_le';

    public const UPDATED_AT = 'modifie_le';

    protected $fillable = [
        'nom',
        'courriel',
        'mot_de_passe',
        'est_administrateur',
        'photo_profil',
        'bio',
    ];

    protected $hidden = [
        'mot_de_passe',
        'jeton_souvenir',
    ];

    protected function casts(): array
    {
        return [
            'courriel_verifie_le' => 'datetime',
            'mot_de_passe' => 'hashed',
            'est_administrateur' => 'boolean',
        ];
    }

    public function getAuthPassword(): string
    {
        return $this->mot_de_passe;
    }

    protected function name(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->nom,
            set: fn (string $value) => ['nom' => $value],
        );
    }

    protected function email(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->courriel,
            set: fn (string $value) => ['courriel' => $value],
        );
    }

    protected function password(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->mot_de_passe,
            set: fn (string $value) => ['mot_de_passe' => $value],
        );
    }

    protected function emailVerifiedAt(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->courriel_verifie_le,
            set: fn ($value) => ['courriel_verifie_le' => $value],
        );
    }

    public function getIsAdminAttribute(): bool
    {
        return (bool) $this->est_administrateur;
    }

    public function setIsAdminAttribute(bool $value): void
    {
        $this->attributes['est_administrateur'] = $value;
    }

    public function isAdmin(): bool
    {
        return (bool) $this->est_administrateur;
    }

    public function posts(): HasMany
    {
        return $this->hasMany(Post::class, 'utilisateur_id');
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class, 'utilisateur_id');
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class, 'utilisateur_id');
    }

    public function profilePhotoUrl(): ?string
    {
        if (! $this->photo_profil) {
            return null;
        }

        if (! Storage::disk('public')->exists($this->photo_profil)) {
            return null;
        }

        return Storage::disk('public')->url($this->photo_profil);
    }

    public function hasProfilePhoto(): bool
    {
        return filled($this->photo_profil) && $this->profilePhotoUrl() !== null;
    }

    public function hasStoredProfilePhoto(): bool
    {
        return filled($this->photo_profil);
    }

    public function profileInitial(): string
    {
        return strtoupper(substr($this->nom ?: '?', 0, 1));
    }
}
