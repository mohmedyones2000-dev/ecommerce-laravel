<?php

namespace App\Notifications;

use App\Models\ProductVariant;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class LowStockNotification extends Notification
{
    use Queueable;

    public function __construct(public ProductVariant $variant) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        $productName = $this->variant->product->name ?? 'منتج';
        $color = $this->variant->color ?? '';
        $size = $this->variant->size ?? '';

        return [
            'title' => 'المخزون منخفض',
            'body'  => $productName . ($color ? " ({$color})" : '') . ($size ? " - {$size}" : '') . ' — بقي ' . $this->variant->stock_quantity . ' قطعة',
            'icon'  => 'heroicon-o-exclamation-triangle',
            'color' => 'warning',
            'url'   => '/admin/product-variants/' . $this->variant->id . '/edit',
        ];
    }

    public function toArray(object $notifiable): array
    {
        return $this->toDatabase($notifiable);
    }
}