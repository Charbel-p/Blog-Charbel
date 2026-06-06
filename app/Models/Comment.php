<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class Comment extends Model
{
    protected $table = 'commentaires';

    public const CREATED_AT = 'cree_le';

    public const UPDATED_AT = 'modifie_le';

    protected $fillable = [
        'article_id',
        'utilisateur_id',
        'commentaire_parent_id',
        'contenu',
        'est_approuve',
    ];

    protected function casts(): array
    {
        return [
            'est_approuve' => 'boolean',
        ];
    }

    protected function content(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->contenu,
            set: fn (string $value) => ['contenu' => $value],
        );
    }

    protected function isApproved(): Attribute
    {
        return Attribute::make(
            get: fn () => (bool) $this->est_approuve,
            set: fn (bool $value) => ['est_approuve' => $value],
        );
    }

    protected function parentId(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->commentaire_parent_id,
            set: fn (?int $value) => ['commentaire_parent_id' => $value],
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

    public function scopeApproved(Builder $query): Builder
    {
        return $query->where('est_approuve', true);
    }

    public function scopeTopLevel(Builder $query): Builder
    {
        return $query->whereNull('commentaire_parent_id');
    }

    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class, 'article_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'utilisateur_id');
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Comment::class, 'commentaire_parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(Comment::class, 'commentaire_parent_id');
    }

    public function likes(): HasMany
    {
        return $this->hasMany(CommentLike::class, 'commentaire_id');
    }

    public function isLikedBy(?User $user): bool
    {
        if ($user === null) {
            return false;
        }

        if (isset($this->liked_by_me)) {
            return (bool) $this->liked_by_me;
        }

        return $this->likes()->where('utilisateur_id', $user->id)->exists();
    }

    public function isReply(): bool
    {
        return $this->commentaire_parent_id !== null;
    }

    public function isOwnedBy(?User $user): bool
    {
        return $user !== null && $this->utilisateur_id === $user->id;
    }

    public function attachChildrenFrom(Collection $comments): void
    {
        $children = $comments
            ->where('commentaire_parent_id', $this->id)
            ->sortByDesc(fn (Comment $comment) => $comment->cree_le)
            ->values();

        $this->setRelation('children', $children);

        foreach ($children as $child) {
            $child->attachChildrenFrom($comments);
        }
    }
}
