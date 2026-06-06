<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Post;
use Illuminate\View\View;

class PostController extends Controller
{
    public function index(): View
    {
        $posts = Post::query()
            ->with('category')
            ->withAvg('reviews', 'note')
            ->withCount(['reviews', 'comments' => fn ($query) => $query->where('est_approuve', true)])
            ->published()
            ->latest('publie_le')
            ->paginate(6);

        return view('posts.index', compact('posts'));
    }

    public function show(Post $post): View
    {
        abort_unless(
            $post->is_published && $post->published_at !== null && $post->published_at->isPast(),
            404
        );

        $commentsQuery = $post->comments()
            ->approved()
            ->with(['user', 'parent.user'])
            ->withCount('likes');

        if (auth()->check()) {
            $commentsQuery->withExists([
                'likes as liked_by_me' => fn ($query) => $query->where('utilisateur_id', auth()->id()),
            ]);
        }

        $approvedComments = $commentsQuery->get();

        $topLevelComments = $approvedComments
            ->whereNull('commentaire_parent_id')
            ->sortByDesc(fn (Comment $comment) => $comment->cree_le)
            ->values();

        $topLevelComments->each(fn (Comment $comment) => $comment->attachChildrenFrom($approvedComments));

        $post->setRelation('comments', $topLevelComments);

        $post->load([
            'category',
            'user',
            'reviews.user',
        ])->loadAvg('reviews', 'note');

        $commentsCount = $approvedComments->count();

        return view('posts.show', compact('post', 'commentsCount'));
    }
}
