<?php

namespace App\Services;

use App\Models\User;
use App\Notifications\LowStockNotification;
use App\Notifications\NewOrderNotification;
use App\Notifications\NewReviewNotification;
use App\Notifications\NewUserNotification;

class AdminNotificationService
{
    public static function notifyUsersWithPermission(string $permission, object $notification): void
    {
        User::whereIn('role', ['admin', 'manager'])
            ->when(auth()->check(), fn ($q) => $q->where('id', '!=', auth()->id()))
            ->get()
            ->each(function (User $user) use ($permission, $notification) {
                if ($user->hasPermission($permission)) {
                    $user->notify($notification);
                }
            });
    }

    public static function orderCreated($order): void
    {
        self::notifyUsersWithPermission('orders', new NewOrderNotification($order));
    }

    public static function userRegistered($user): void
    {
        self::notifyUsersWithPermission('users', new NewUserNotification($user));
    }

    public static function reviewCreated($review): void
    {
        self::notifyUsersWithPermission('reviews', new NewReviewNotification($review));
    }

    public static function lowStock($variant): void
    {
        self::notifyUsersWithPermission('products', new LowStockNotification($variant));
    }
}