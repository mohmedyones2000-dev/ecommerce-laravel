<?php

namespace App\Filament\Resources\OrderResource\Pages;

use App\Filament\Resources\OrderResource;
use App\Services\NotificationService;
use Filament\Resources\Pages\EditRecord;

class EditOrder extends EditRecord
{
    protected static string $resource = OrderResource::class;

    protected ?string $originalStatus = null;

    protected function getHeaderActions(): array
    {
        return [];
    }

    protected function beforeSave(): void
    {
        $this->originalStatus = $this->record->getOriginal('status');
    }

    protected function afterSave(): void
    {
        $order = $this->record;

        if ($this->originalStatus !== $order->status) {
            NotificationService::orderStatusChanged($order, $order->status);
        }
    }
}