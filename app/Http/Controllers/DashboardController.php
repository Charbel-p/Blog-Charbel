<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(Request $request): View
    {
        $user = $request->user();

        $myComments = $user->isAdmin()
            ? collect()
            : $user->comments()
                ->with('post')
                ->latest()
                ->paginate(10);

        return view('dashboard', compact('myComments'));
    }
}
