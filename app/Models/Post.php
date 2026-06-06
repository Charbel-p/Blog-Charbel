<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Post extends Model
{
    protected $table = 'articles';

    public const CREATED_AT = 'cree_le';

    public const UPDATED_AT = 'modifie_le';

    protected $fillable = [
        'utilisateur_id',
        'categorie_id',
        'titre',
        'libelle_url',
        'resume',
        'image_couverture',
        'contenu',
        'est_publie',
        'publie_le',
    ];

    protected function casts(): array
    {
        return [
            'est_publie' => 'boolean',
            'publie_le' => 'datetime',
        ];
    }

    public function getRouteKeyName(): string
    {
        return 'libelle_url';
    }

    protected function title(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->titre,
            set: fn (string $value) => ['titre' => $value],
        );
    }

    protected function slug(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->libelle_url,
            set: fn (string $value) => ['libelle_url' => $value],
        );
    }

    protected function excerpt(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->resume,
            set: fn (?string $value) => ['resume' => $value],
        );
    }

    protected function coverImage(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->image_couverture,
            set: fn (?string $value) => ['image_couverture' => $value],
        );
    }

    protected function content(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->contenu,
            set: fn (string $value) => ['contenu' => $value],
        );
    }

    protected function isPublished(): Attribute
    {
        return Attribute::make(
            get: fn () => (bool) $this->est_publie,
            set: fn (bool $value) => ['est_publie' => $value],
        );
    }

    protected function publishedAt(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->publie_le,
            set: fn ($value) => ['publie_le' => $value],
        );
    }

    protected function userId(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->utilisateur_id,
            set: fn (int $value) => ['utilisateur_id' => $value],
        );
    }

    protected function categoryId(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->categorie_id,
            set: fn (int $value) => ['categorie_id' => $value],
        );
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query
            ->where('est_publie', true)
            ->whereNotNull('publie_le')
            ->where('publie_le', '<=', now());
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'utilisateur_id');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'categorie_id');
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class, 'article_id');
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class, 'article_id');
    }

    public function coverImageUrl(): ?string
    {
        if (! $this->image_couverture) {
            return null;
        }

        if (! Storage::disk('public')->exists($this->image_couverture)) {
            return null;
        }

        return Storage::disk('public')->url($this->image_couverture);
    }

    public function hasCoverImage(): bool
    {
        return $this->coverImageUrl() !== null;
    }

    public function getReviewsAvgRatingAttribute(): ?float
    {
        $value = $this->attributes['reviews_avg_note'] ?? null;

        return $value !== null ? (float) $value : null;
    }
}
