<?php

namespace App\Providers;

use App\Models\Notification;
use App\Models\SiteSetting;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        $this->registerNotificationEvents();
        $this->shareSiteSettings();
        $this->configureModels();
    }

    protected function registerNotificationEvents(): void
    {
        Notification::created(function (Notification $notification) {
            Cache::forget("notif_count_user_{$notification->user_id}");
        });

        Notification::updated(function (Notification $notification) {
            Cache::forget("notif_count_user_{$notification->user_id}");
        });

        Notification::deleted(function (Notification $notification) {
            Cache::forget("notif_count_user_{$notification->user_id}");
        });
    }

    protected function shareSiteSettings(): void
    {
        View::composer('*', function ($view) {
            $view->with('siteSettings', SiteSetting::current());
        });
    }

    protected function configureModels(): void
    {
        Model::shouldBeStrict(!$this->app->isProduction());
    }
}