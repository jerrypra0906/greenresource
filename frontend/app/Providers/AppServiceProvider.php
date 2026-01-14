<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\NavigationItem;

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
        // Share navigation items with the app layout
        View::composer('layouts.app', function ($view) {
            $menuItems = NavigationItem::whereNull('parent_id')
                ->where('visible', true)
                ->with(['children' => function ($query) {
                    $query->where('visible', true)->orderBy('order');
                }])
                ->orderBy('order')
                ->get();
            
            $view->with('menuItems', $menuItems);
        });
    }
}

