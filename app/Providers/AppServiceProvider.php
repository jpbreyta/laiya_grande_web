<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\ContactMessage;
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
            $unreadCount = ContactMessage::unread()->count();
            $notificationCount = \App\Models\Notification::unread()->count();
            $view->with('unreadCount', $unreadCount);
            $view->with('notificationCount', $notificationCount);
        });
    }
}
