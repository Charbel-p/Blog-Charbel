<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\CommentLike;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CommentLikeController extends Controller
{
    public function toggle(Request $request, Comment $comment): RedirectResponse
    {
        abort_unless($comment->est_approuve, 404);

        $existing = CommentLike::query()
            ->where('commentaire_id', $comment->id)
            ->where('utilisateur_id', $request->user()->id)
            ->first();

        if ($existing) {
            $existing->delete();
        } else {
            CommentLike::create([
                'commentaire_id' => $comment->id,
                'utilisateur_id' => $request->user()->id,
            ]);
        }

        return back()->withFragment('comment-'.$comment->id);
    }
}
