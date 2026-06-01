<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CommentModerationController extends Controller
{
    public function index(): View
    {
        $comments = Comment::query()
            ->with(['post', 'user'])
            ->latest()
            ->paginate(20);

        return view('admin.comments.index', compact('comments'));
    }

    public function approve(Comment $comment): RedirectResponse
    {
        $comment->update(['is_approved' => true]);
        return back()->with('status', 'Commentaire approuve.');
    }

    public function reject(Comment $comment): RedirectResponse
    {
        $comment->delete();
        return back()->with('status', 'Commentaire supprime.');
    }
}
