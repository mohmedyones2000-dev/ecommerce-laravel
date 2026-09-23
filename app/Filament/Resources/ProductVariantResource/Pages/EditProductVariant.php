<?php

namespace App\Filament\Resources\ProductVariantResource\Pages;

use App\Filament\Resources\ProductVariantResource;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

class EditProductVariant extends EditRecord
{
    protected static string $resource = ProductVariantResource::class;

    public function getTitle(): string
    {
        return 'تعديل المتغير';
    }

    protected function getRedirectUrl(): string
    {
        return ProductVariantResource::getUrl('edit', ['record' => $this->record]);
    }

    protected function getSavedNotification(): ?Notification
    {
        return Notification::make()
            ->success()
            ->title('تم حفظ التعديلات')
            ->icon('heroicon-o-check-circle')
            ->send();
    }

    protected function getFormActions(): array
    {
        return [
            $this->getSaveFormAction()->label('حفظ')->icon('heroicon-o-check'),
            $this->getCancelFormAction()->label('إلغاء')->icon('heroicon-o-x-mark'),
        ];
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()
                ->label('حذف المتغير')
                ->icon('heroicon-o-trash')
                ->requiresConfirmation(),
        ];
    }
}