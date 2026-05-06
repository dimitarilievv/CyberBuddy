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
        // On platforms like Render, TLS is terminated at the edge proxy.
        // If proxy headers aren't honored for any reason, this ensures generated URLs stay HTTPS.
        if ($this->app->environment('production')) {
            URL::forceScheme('https');
        }
    }
}
