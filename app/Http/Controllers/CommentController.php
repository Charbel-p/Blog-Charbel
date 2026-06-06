<?php

namespace App\Http\Controllers;

use App\Models\Comment;
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
                Rule::exists('commentaires', 'id')->where(fn ($query) => $query->where('article_id', $post->id)),
            ],
        ]);

        $comment = $post->comments()->create([
            'utilisateur_id' => $request->user()->id,
            'commentaire_parent_id' => $validated['parent_id'] ?? null,
            'contenu' => $validated['content'],
            'est_approuve' => true,
        ]);

        $message = $comment->isReply()
            ? 'Votre réponse a été publiée.'
            : 'Votre commentaire a été publié.';

        return back()
            ->with('status', $message)
            ->withFragment('comment-'.$comment->id);
    }

    public function update(Request $request, Comment $comment): RedirectResponse
    {
        $this->authorize('update', $comment);

        $validated = $request->validate([
            'content' => ['required', 'string', 'min:3', 'max:1500'],
        ]);

        $comment->update([
            'contenu' => $validated['content'],
        ]);

        return back()
            ->with('status', 'Votre commentaire a été modifié.')
            ->withFragment('comment-'.$comment->id);
    }

    public function destroy(Request $request, Comment $comment): RedirectResponse
    {
        $this->authorize('delete', $comment);

        $comment->delete();

        return back()->with('status', 'Votre commentaire a été supprimé.');
    }
}
