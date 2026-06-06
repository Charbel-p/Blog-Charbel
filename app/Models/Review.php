<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $table = 'avis';

    public const CREATED_AT = 'cree_le';

    public const UPDATED_AT = 'modifie_le';

    protected $fillable = [
        'article_id',
        'utilisateur_id',
        'note',
        'texte_avis',
    ];

    protected function rating(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->note,
            set: fn (int $value) => ['note' => $value],
        );
    }

    protected function opinion(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->texte_avis,
            set: fn (?string $value) => ['texte_avis' => $value],
        );
    }

    protected function postId(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->article_id,
            set: fn (int $value) => ['article_id' => $value],
        );
    }

    protected function userId(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->utilisateur_id,
            set: fn (int $value) => ['utilisateur_id' => $value],
        );
    }

    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class, 'article_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'utilisateur_id');
    }
}
