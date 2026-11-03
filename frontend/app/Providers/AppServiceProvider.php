<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
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
        // Admin uses styles.css (no Tailwind); default Laravel pagination breaks without it.
        Paginator::defaultView('pagination.admin');
        Paginator::defaultSimpleView('pagination.admin');
    }
}

