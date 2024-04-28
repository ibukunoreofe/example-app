<?php

namespace App\Providers;

use Illuminate\Support\Facades\URL;
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
        URL::forceRootUrl(env('APP_URL'));
        //TODO: Check if it still works without this line since X-PROTO is enabled
//        URL::forceScheme(parse_url( env('APP_URL'), PHP_URL_SCHEME ));
    }
}
