<?php

use App\Http\Controllers\Admin\CommentModerationController;
use App\Http\Controllers\Admin\PostController as AdminPostController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ReviewController;
use Illuminate\Support\Facades\Route;

// Healthcheck Railway (sans base de donnees)
Route::get('/ping', fn () => response('OK', 200));

// Diagnostic deploy (a retirer plus tard si besoin)
Route::get('/debug-deploy', function () {
    try {
        \Illuminate\Support\Facades\DB::connection()->getPdo();

        return response()->json([
            'status' => 'ok',
            'database' => 'connected',
            'vite_manifest' => file_exists(public_path('build/manifest.json')),
            'app_debug' => config('app.debug'),
        ]);
    } catch (\Throwable $e) {
        return response()->json([
            'status' => 'error',
            'message' => $e->getMessage(),
            'vite_manifest' => file_exists(public_path('build/manifest.json')),
        ], 500);
    }
});

Route::get('/', [PostController::class, 'index'])->name('posts.index');
Route::get('/posts/{post:slug}', [PostController::class, 'show'])->name('posts.show');

Route::get('/dashboard', DashboardController::class)
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::post('/posts/{post}/comments', [CommentController::class, 'store'])->name('comments.store');
    Route::post('/posts/{post}/reviews', [ReviewController::class, 'store'])->name('reviews.store');

    Route::prefix('admin')->name('admin.')->middleware('admin')->group(function () {
        Route::resource('posts', AdminPostController::class);
        Route::get('/comments', [CommentModerationController::class, 'index'])->name('comments.index');
        Route::patch('/comments/{comment}/approve', [CommentModerationController::class, 'approve'])->name('comments.approve');
        Route::delete('/comments/{comment}/reject', [CommentModerationController::class, 'reject'])->name('comments.reject');
    });

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
