<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;

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
        \Illuminate\Support\Facades\Event::listen(
            \Illuminate\Auth\Events\Login::class,
            \App\Listeners\LogSuccessfulLogin::class,
        );

        \Illuminate\Support\Facades\Event::listen(
            \Illuminate\Auth\Events\Logout::class,
            \App\Listeners\LogSuccessfulLogout::class,
        );

        if (config('app.env') !== 'production') {
            //URL::forceScheme('https');
        }
    }
}
