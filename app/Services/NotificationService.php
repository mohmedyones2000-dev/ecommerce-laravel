<?php

namespace App\Services;

use App\Models\Notification;
use App\Models\Order;

class NotificationService
{
    public static function login(int $userId): void
    {
        $recentWelcome = Notification::where('user_id', $userId)
            ->where('icon', 'login')
            ->where('created_at', '>=', now()->subHours(12))
            ->exists();

        if ($recentWelcome) {
            return;
        }

        Notification::create([
            'user_id' => $userId,
            'title'   => 'مرحباً بعودتك',
            'message' => 'تم تسجيل الدخول بنجاح. نتمنى لك تجربة تسوق ممتعة.',
            'type'    => 'success',
            'icon'    => 'login',
            'link'    => route('home'),
        ]);
    }

    public static function cartAdded(int $userId, string $productName, int $quantity = 1): void
    {
        $message = $quantity > 1
            ? $quantity . ' × ' . $productName
            : $productName;

        Notification::create([
            'user_id' => $userId,
            'title'   => 'تمت الإضافة إلى السلة',
            'message' => $message,
            'type'    => 'success',
            'icon'    => 'cart',
            'link'    => route('cart.index'),
        ]);
    }

    public static function cartRemoved(int $userId, string $productName): void
    {
        Notification::create([
            'user_id' => $userId,
            'title'   => 'تم الحذف من السلة',
            'message' => $productName,
            'type'    => 'warning',
            'icon'    => 'cart',
            'link'    => route('cart.index'),
        ]);
    }

    public static function wishlistAdded(int $userId, string $productName): void
    {
        Notification::create([
            'user_id' => $userId,
            'title'   => 'أضيف إلى المفضلة',
            'message' => $productName,
            'type'    => 'success',
            'icon'    => 'wishlist',
            'link'    => route('wishlist.index'),
        ]);
    }

    public static function wishlistRemoved(int $userId, string $productName): void
    {
        Notification::create([
            'user_id' => $userId,
            'title'   => 'حُذف من المفضلة',
            'message' => $productName,
            'type'    => 'warning',
            'icon'    => 'wishlist',
            'link'    => route('wishlist.index'),
        ]);
    }

    public static function orderPlaced(Order $order): void
    {
        Notification::create([
            'user_id' => $order->user_id,
            'title'   => 'تم استلام طلبك',
            'message' => 'طلب رقم ' . $order->order_number . ' بقيمة $' . number_format($order->total_amount, 2) . ' — قيد المراجعة',
            'type'    => 'success',
            'icon'    => 'order',
            'link'    => route('orders.show', $order->id),
        ]);
    }

    public static function orderStatusChanged(Order $order, string $newStatus): void
    {
        $config = match ($newStatus) {
            'pending' => [
                'title'   => 'طلبك قيد المراجعة',
                'message' => 'طلب رقم ' . $order->order_number . ' قيد المراجعة',
                'type'    => 'info',
                'icon'    => 'order',
            ],
            'processing' => [
                'title'   => 'طلبك قيد المعالجة',
                'message' => 'طلب رقم ' . $order->order_number . ' قيد المعالجة الآن',
                'type'    => 'info',
                'icon'    => 'order',
            ],
            'shipped' => [
                'title'   => 'تم شحن طلبك',
                'message' => 'طلب رقم ' . $order->order_number . ' في الطريق إليك',
                'type'    => 'success',
                'icon'    => 'shipping',
            ],
            'delivered' => [
                'title'   => 'تم توصيل طلبك',
                'message' => 'طلب رقم ' . $order->order_number . ' وصل بنجاح. نتمنى أن تكون سعيداً بالشراء',
                'type'    => 'success',
                'icon'    => 'delivered',
            ],
            'cancelled' => [
                'title'   => 'تم إلغاء طلبك',
                'message' => 'طلب رقم ' . $order->order_number . ' تم إلغاؤه',
                'type'    => 'danger',
                'icon'    => 'cancelled',
            ],
            default => [
                'title'   => 'تحديث على طلبك',
                'message' => 'طلب رقم ' . $order->order_number,
                'type'    => 'info',
                'icon'    => 'order',
            ],
        };

        Notification::create([
            'user_id' => $order->user_id,
            'title'   => $config['title'],
            'message' => $config['message'],
            'type'    => $config['type'],
            'icon'    => $config['icon'],
            'link'    => route('orders.show', $order->id),
        ]);
    }
}