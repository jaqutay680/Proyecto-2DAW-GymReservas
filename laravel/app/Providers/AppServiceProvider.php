<?php

namespace App\Providers;

use Illuminate\Support\Facades\Vite;
use Illuminate\Support\ServiceProvider;
use Laravel\Socialite\Facades\Socialite;

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
        Vite::prefetch(concurrency: 3);

        // Reemplazar el driver de Google de Socialite por nuestro provider
        // personalizado que usa el endpoint de tokens actualizado de Google.
        Socialite::extend('google', function ($app) {
            $config = $app['config']['services.google'];

            return Socialite::buildProvider(
                \App\Socialite\GoogleProvider::class,
                $config
            );
        });
    }
}
