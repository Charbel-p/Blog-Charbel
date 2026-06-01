<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\View\View;

class PostController extends Controller
{
    public function index(): View
    {
        $posts = Post::query()
            ->with('category')
            ->withAvg('reviews', 'rating')
            ->withCount(['reviews', 'comments' => fn ($query) => $query->where('is_approved', true)])
            ->published()
            ->latest('published_at')
            ->paginate(6);

        return view('posts.index', compact('posts'));
    }

    public function show(Post $post): View
    {
        abort_unless(
            $post->is_published && $post->published_at !== null && $post->published_at->isPast(),
            404
        );

        $post->load([
            'category',
            'user',
            'comments' => fn ($query) => $query->where('is_approved', true)->with('user')->latest(),
            'reviews.user',
        ])->loadAvg('reviews', 'rating');

        return view('posts.show', compact('post'));
    }
}
