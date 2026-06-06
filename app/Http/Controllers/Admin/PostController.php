<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class PostController extends Controller
{
    public function index(): View
    {
        $posts = Post::query()
            ->with(['category', 'user'])
            ->latest()
            ->paginate(12);

        return view('admin.posts.index', compact('posts'));
    }

    public function create(): View
    {
        $categories = Category::query()->orderBy('nom')->get();
        return view('admin.posts.create', compact('categories'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'excerpt' => ['nullable', 'string', 'max:500'],
            'content' => ['required', 'string'],
            'category_id' => ['required', 'exists:categories,id'],
            'cover_image' => ['nullable', 'image', 'mimes:jpeg,jpg,png,webp', 'max:2048'],
            'is_published' => ['nullable', 'boolean'],
        ]);

        $slug = Str::slug($validated['title']);
        $count = Post::where('libelle_url', 'like', $slug.'%')->count();
        $finalSlug = $count ? "{$slug}-".($count + 1) : $slug;

        $coverImagePath = null;
        if ($request->hasFile('cover_image')) {
            $coverImagePath = $request->file('cover_image')->store('posts/covers', 'public');
        }

        Post::create([
            'titre' => $validated['title'],
            'resume' => $validated['excerpt'] ?? null,
            'contenu' => $validated['content'],
            'categorie_id' => $validated['category_id'],
            'image_couverture' => $coverImagePath,
            'libelle_url' => $finalSlug,
            'utilisateur_id' => $request->user()->id,
            'est_publie' => (bool) ($validated['is_published'] ?? false),
            'publie_le' => ($validated['is_published'] ?? false) ? now() : null,
        ]);

        return redirect()->route('admin.posts.index')->with('status', 'Article cree avec succes.');
    }

    public function show(Post $post): View
    {
        $allComments = $post->comments()->with(['user', 'parent.user'])->get();

        $topLevelComments = $allComments
            ->whereNull('commentaire_parent_id')
            ->sortByDesc(fn (Comment $comment) => $comment->cree_le)
            ->values();

        $topLevelComments->each(fn (Comment $comment) => $comment->attachChildrenFrom($allComments));

        $post->setRelation('comments', $topLevelComments);
        $post->load(['category', 'user', 'reviews.user']);

        $commentsCount = $allComments->count();

        return view('admin.posts.show', compact('post', 'commentsCount'));
    }

    public function edit(Post $post): View
    {
        $categories = Category::query()->orderBy('nom')->get();
        return view('admin.posts.edit', compact('post', 'categories'));
    }

    public function update(Request $request, Post $post): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'excerpt' => ['nullable', 'string', 'max:500'],
            'content' => ['required', 'string'],
            'category_id' => ['required', 'exists:categories,id'],
            'cover_image' => ['nullable', 'image', 'mimes:jpeg,jpg,png,webp', 'max:2048'],
            'is_published' => ['nullable', 'boolean'],
        ]);

        $slug = Str::slug($validated['title']);
        if ($slug !== $post->libelle_url) {
            $count = Post::where('libelle_url', 'like', $slug.'%')->where('id', '!=', $post->id)->count();
            $post->libelle_url = $count ? "{$slug}-".($count + 1) : $slug;
        }

        if ($request->hasFile('cover_image')) {
            if ($post->image_couverture) {
                Storage::disk('public')->delete($post->image_couverture);
            }
            $validated['cover_image'] = $request->file('cover_image')->store('posts/covers', 'public');
        } else {
            unset($validated['cover_image']);
        }

        $willPublish = (bool) ($validated['is_published'] ?? false);
        $post->titre = $validated['title'];
        $post->resume = $validated['excerpt'] ?? null;
        $post->contenu = $validated['content'];
        $post->categorie_id = $validated['category_id'];
        if (isset($validated['cover_image'])) {
            $post->image_couverture = $validated['cover_image'];
        }
        $post->est_publie = $willPublish;
        $post->publie_le = $willPublish ? ($post->publie_le ?? now()) : null;
        $post->save();

        return redirect()->route('admin.posts.index')->with('status', 'Article mis a jour.');
    }

    public function destroy(Post $post): RedirectResponse
    {
        if ($post->image_couverture) {
            Storage::disk('public')->delete($post->image_couverture);
        }

        $post->delete();

        return redirect()->route('admin.posts.index')->with('status', 'Article supprime.');
    }
}
