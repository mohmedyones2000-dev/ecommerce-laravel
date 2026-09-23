<?php

namespace App\Filament\Resources\SizeGuideResource\Pages;

use App\Filament\Resources\SizeGuideResource;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;

class CreateSizeGuide extends CreateRecord
{
    protected static string $resource = SizeGuideResource::class;

    public function getTitle(): string
    {
        return 'إضافة دليل مقاسات';
    }

    public function getBreadcrumbs(): array
    {
        return [
            'الرئيسية' => route('filament.admin.pages.dashboard'),
            'أدلة المقاسات' => SizeGuideResource::getUrl('index'),
            'إضافة دليل',
        ];
    }

    protected function getRedirectUrl(): string
    {
        return SizeGuideResource::getUrl('edit', ['record' => $this->record]);
    }

    protected function getCreatedNotificationTitle(): ?string
    {
        return 'تم إنشاء دليل المقاسات بنجاح';
    }

    protected function getCreatedNotification(): ?Notification
    {
        return Notification::make()
            ->success()
            ->title('تم إنشاء دليل المقاسات')
            ->body("الدليل \"{$this->record->name}\" أُضيف بنجاح. يمكنك الآن إضافة المقاسات من صفحة التعديل.")
            ->icon('heroicon-o-check-circle')
            ->duration(6000);
    }

    protected function getFormActions(): array
    {
        return [
            $this->getCreateFormAction()
                ->label('حفظ الدليل')
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