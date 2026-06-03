<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\Rule;

class CommentController extends Controller
{
    public function store(Request $request, Post $post): RedirectResponse
    {
        $validated = $request->validate([
            'content' => ['required', 'string', 'min:3', 'max:1500'],
            'parent_id' => [
                'nullable',
                'integer',
                Rule::exists('comments', 'id')->where(fn ($query) => $query->where('post_id', $post->id)),
            ],
        ]);

        $comment = $post->comments()->create([
            'user_id' => $request->user()->id,
            'parent_id' => $validated['parent_id'] ?? null,
            'content' => $validated['content'],
            'is_approved' => true,
        ]);

        $message = $comment->isReply()
            ? 'Votre réponse a été publiée.'
            : 'Votre commentaire a été publié.';

        return back()
            ->with('status', $message)
            ->withFragment('comment-'.$comment->id);
    }
}
