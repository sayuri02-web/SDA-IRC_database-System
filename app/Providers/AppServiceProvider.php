<?php

namespace App\Providers;

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
        // Register Blade directive for role-based UI
        \Illuminate\Support\Facades\Blade::if('canManage', function (string $module) {
            $user = auth()->user();
            return $user && $user->hasAccessTo($module);
        });
    }
}
