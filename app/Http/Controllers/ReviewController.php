<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;

class ReviewController extends Controller
{
    public function store(Request $request, Post $post): RedirectResponse
    {
        $validated = $request->validate([
            'rating' => ['required', 'integer', 'between:1,5'],
            'opinion' => ['nullable', 'string', 'max:500'],
        ]);

        $post->reviews()->updateOrCreate(
            ['user_id' => $request->user()->id],
            [
                'rating' => $validated['rating'],
                'opinion' => $validated['opinion'] ?? null,
            ]
        );

        return back()->with('status', 'Votre note a bien été enregistrée.');
    }
}
