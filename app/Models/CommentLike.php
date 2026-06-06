<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;

class CommentLike extends Model
{
    protected $table = 'commentaire_likes';

    public const CREATED_AT = 'cree_le';

    public const UPDATED_AT = 'modifie_le';

    protected $fillable = [
        'commentaire_id',
        'utilisateur_id',
    ];

    public function comment(): BelongsTo
    {
        return $this->belongsTo(Comment::class, 'commentaire_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'utilisateur_id');
    }
}
