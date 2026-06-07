<?php

namespace App\Providers;

use App\Auth\CourrielUserProvider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Auth::provider('courriel', function ($app, array $config) {
            return new CourrielUserProvider($app['hash'], $config['model']);
        });

        $publicStorage = public_path('storage');
        $appPublic = storage_path('app/public');

        if (! File::exists($publicStorage) && File::isDirectory($appPublic)) {
            File::link($appPublic, $publicStorage);
        }
    }
}
