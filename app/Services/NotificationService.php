<?php

namespace App\Services;

use App\Models\Notification;

class NotificationService
{
    public static function send(int $userId, string $title, string $message, array $options = []): Notification
    {
        return Notification::create([
            'user_id' => $userId,
            'title' => $title,
            'message' => $message,
            'type' => $options['type'] ?? 'info',
            'icon' => $options['icon'] ?? 'bell',
            'link' => $options['link'] ?? null,
        ]);
    }

    public static function orderStatusChanged($order, string $newStatus): void
    {
        $messages = [
            'pending' => [
                'title' => 'تم استلام طلبك',
                'message' => "طلبك رقم {$order->order_number} قيد المراجعة حالياً.",
                'icon' => 'order',
                'type' => 'info',
            ],
            'processing' => [
                'title' => 'طلبك قيد المعالجة',
                'message' => "نعمل حالياً على تجهيز طلبك رقم {$order->order_number}.",
                'icon' => 'order',
                'type' => 'info',
            ],
            'shipped' => [
                'title' => 'تم شحن طلبك',
                'message' => "طلبك رقم {$order->order_number} في الطريق إليك.",
                'icon' => 'shipping',
                'type' => 'info',
            ],
            'delivered' => [
                'title' => 'تم توصيل طلبك',
                'message' => "نتمنى أن تكون سعيداً بطلبك رقم {$order->order_number}. لا تنسَ تقييم المنتجات.",
                'icon' => 'delivered',
                'type' => 'success',
            ],
            'cancelled' => [
                'title' => 'تم إلغاء طلبك',
                'message' => "تم إلغاء طلبك رقم {$order->order_number}. إذا كان لديك استفسار، تواصل معنا.",
                'icon' => 'cancelled',
                'type' => 'danger',
            ],
        ];

        if (!isset($messages[$newStatus])) return;

        $data = $messages[$newStatus];

        self::send($order->user_id, $data['title'], $data['message'], [
            'type' => $data['type'],
            'icon' => $data['icon'],
            'link' => route('orders.show', $order->id),
        ]);
    }

    public static function welcome(int $userId, string $name): void
    {
        $hasWelcomeCoupon = \App\Models\Coupon::where('code', 'WELCOME10')->exists();

        $message = "أهلاً {$name}! نتمنى لك تجربة تسوق رائعة.";

        if ($hasWelcomeCoupon) {
            $message .= " استخدم كود WELCOME10 للحصول على خصم 10%.";
        }

        self::send($userId, 'مرحباً بك في متجري', $message, [
            'type' => 'success',
            'icon' => 'promo',
            'link' => route('products.index'),
        ]);
    }
}