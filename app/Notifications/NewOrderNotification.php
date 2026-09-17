<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class NewOrderNotification extends Notification
{
    use Queueable;

    public function __construct(public Order $order) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

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

    public function toArray(object $notifiable): array
    {
        return $this->toDatabase($notifiable);
    }
}