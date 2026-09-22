<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;

class NewOrderNotification extends Notification implements ShouldBroadcast
{
    use Queueable;

    public function __construct(public Order $order) {}

    /**
     * القنوات: database (للحفظ) + broadcast (للحظي)
     */
    public function via(object $notifiable): array
    {
        return ['database', 'broadcast'];
    }

    /**
     * بيانات الإشعار في قاعدة البيانات
     */
    public function toDatabase(object $notifiable): array
    {
        return [
            'title' => 'طلب جديد',
            'body'  => 'طلب رقم ' . $this->order->order_number . ' بقيمة $' . number_format($this->order->total_amount, 2),
            'icon'  => 'heroicon-o-shopping-cart',
            'color' => 'success',
            'url'   => '/admin/orders/' . $this->order->id . '/edit',
        ];
    }

    /**
     * بيانات الإشعار المُبثّ لحظياً عبر WebSocket
     */
    public function toBroadcast(object $notifiable): BroadcastMessage
    {
        return new BroadcastMessage([
            'id'         => $this->id,
            'title'      => 'طلب جديد',
            'message'    => 'طلب رقم ' . $this->order->order_number,
            'icon'       => 'cart',
            'color'      => 'success',
            'url'        => '/admin/orders/' . $this->order->id . '/edit',
            'created_at' => now()->toISOString(),
        ]);
    }

    /**
     * القناة الخاصة التي يُبث عليها الإشعار
     */
    public function broadcastOn(): array
    {
        // نبث على قناة الإدارة (كل من له صلاحية orders)
        // نستخدم قناة عامة للمشرفين
        return [
            new PrivateChannel('admin.notifications'),
        ];
    }

    /**
     * اسم الحدث (للتوضيح في JS)
     */
    public function broadcastAs(): string
    {
        return 'new.order';
    }
}