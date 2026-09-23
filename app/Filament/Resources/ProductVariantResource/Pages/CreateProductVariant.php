<?php

namespace App\Filament\Resources\ProductVariantResource\Pages;

use App\Filament\Resources\ProductVariantResource;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;

class CreateProductVariant extends CreateRecord
{
    protected static string $resource = ProductVariantResource::class;

    public function getTitle(): string
    {
        return 'إضافة متغير جديد';
    }

    protected function getRedirectUrl(): string
    {
        return ProductVariantResource::getUrl('edit', ['record' => $this->record]);
    }

    protected function getCreatedNotification(): ?Notification
    {
        return Notification::make()
            ->success()
            ->title('تم إنشاء المتغير')
            ->icon('heroicon-o-check-circle')
            ->send();
    }

    protected function getFormActions(): array
    {
        return [
            $this->getCreateFormAction()->label('حفظ')->icon('heroicon-o-check'),
            $this->getCreateAnotherFormAction()->label('حفظ وإضافة آخر')->icon('heroicon-o-plus-circle'),
            $this->getCancelFormAction()->label('إلغاء')->icon('heroicon-o-x-mark'),
        ];
    }
}