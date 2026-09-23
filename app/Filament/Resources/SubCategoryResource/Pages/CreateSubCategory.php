<?php

namespace App\Filament\Resources\SubCategoryResource\Pages;

use App\Filament\Resources\SubCategoryResource;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;

class CreateSubCategory extends CreateRecord
{
    protected static string $resource = SubCategoryResource::class;

    public function getTitle(): string
    {
        return 'إضافة تصنيف فرعي';
    }

    public function getBreadcrumbs(): array
    {
        return [
            'الرئيسية' => route('filament.admin.pages.dashboard'),
            'التصنيفات الفرعية' => SubCategoryResource::getUrl('index'),
            'إضافة تصنيف فرعي',
        ];
    }

    protected function getRedirectUrl(): string
    {
        return SubCategoryResource::getUrl('edit', ['record' => $this->record]);
    }

    protected function getCreatedNotificationTitle(): ?string
    {
        return 'تم إنشاء التصنيف الفرعي بنجاح';
    }

    protected function getCreatedNotification(): ?Notification
    {
        return Notification::make()
            ->success()
            ->title('تم إنشاء التصنيف الفرعي')
            ->body("التصنيف الفرعي \"{$this->record->name}\" أُضيف بنجاح.")
            ->icon('heroicon-o-check-circle')
            ->duration(5000);
    }

    protected function getFormActions(): array
    {
        return [
            $this->getCreateFormAction()
                ->label('حفظ التصنيف الفرعي')
                ->icon('heroicon-o-check'),

            $this->getCreateAnotherFormAction()
                ->label('حفظ وإضافة آخر')
                ->icon('heroicon-o-plus-circle'),

            $this->getCancelFormAction()
                ->label('إلغاء')
                ->icon('heroicon-o-x-mark'),
        ];
    }
}