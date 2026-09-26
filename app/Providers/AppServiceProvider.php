<?php

namespace App\Providers;

use App\Models\Notification as UserNotification;
use App\Models\Order;
use App\Models\ProductImage;
use App\Models\ProductVariant;
use App\Models\Review;
use App\Models\SiteSetting;
use App\Models\User;
use App\Observers\ProductImageObserver;
use App\Services\AdminNotificationService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\URL;
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
        $this->forceHttpsInProduction();
        $this->registerObservers();
        $this->registerUserNotificationEvents();
        $this->registerAdminNotificationEvents();
        $this->shareSiteSettings();
        $this->configureModels();
    }

    protected function forceHttpsInProduction(): void
    {
        if ($this->app->isProduction()) {
            URL::forceScheme('https');
        }
    }

    protected function registerObservers(): void
    {
        ProductImage::observe(ProductImageObserver::class);
    }

    protected function registerUserNotificationEvents(): void
    {
        UserNotification::created(function (UserNotification $notification) {
            Cache::forget("notif_count_user_{$notification->user_id}");
        });

        UserNotification::updated(function (UserNotification $notification) {
            Cache::forget("notif_count_user_{$notification->user_id}");
        });

        UserNotification::deleted(function (UserNotification $notification) {
            Cache::forget("notif_count_user_{$notification->user_id}");
        });
    }

    protected function registerAdminNotificationEvents(): void
    {
        Order::created(function (Order $order) {
            AdminNotificationService::orderCreated($order);
        });

        User::created(function (User $user) {
            if ($user->role === 'customer') {
                AdminNotificationService::userRegistered($user);
            }
        });

        Review::created(function (Review $review) {
            AdminNotificationService::reviewCreated($review);
        });

        ProductVariant::updated(function (ProductVariant $variant) {
            if ($variant->wasChanged('stock_quantity')) {
                $newStock = $variant->stock_quantity;
                $oldStock = $variant->getOriginal('stock_quantity');

                if (($newStock <= 5 && $newStock > 0 && $oldStock > 5) ||
                    ($newStock === 0 && $oldStock > 0)) {
                    AdminNotificationService::lowStock($variant);
                }
            }
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
        Model::preventLazyLoading(false);
        Model::preventSilentlyDiscardingAttributes(!$this->app->isProduction());
        Model::preventAccessingMissingAttributes(!$this->app->isProduction());
    }
}