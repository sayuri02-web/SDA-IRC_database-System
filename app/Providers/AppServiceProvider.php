<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Notification;
use Illuminate\Support\Facades\View;

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
        View::composer('*', function ($view) {

            $notifications = Notification::latest()
                ->take(10)
                ->get();

            $unreadNotifications = Notification::where('is_read', false)
                ->count();

            $view->with('notifications', $notifications);
            $view->with('unreadNotifications', $unreadNotifications);
        });
    }
}
