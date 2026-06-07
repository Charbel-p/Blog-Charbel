<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\View\View;

class UserProfileController extends Controller
{
    public function show(User $user): View
    {
        $user->loadCount(['comments', 'reviews']);

        $recentComments = $user->comments()
            ->approved()
            ->with('post')
            ->latest('cree_le')
            ->limit(8)
            ->get();

        return view('users.show', compact('user', 'recentComments'));
    }
}
