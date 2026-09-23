<?php

namespace App\Filament\Resources\CouponResource\Pages;

use App\Filament\Resources\CouponResource;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;

class CreateCoupon extends CreateRecord
{
    protected static string $resource = CouponResource::class;

    public function getTitle(): string
    {
        return 'إضافة كوبون خصم';
    }

    public function getBreadcrumbs(): array
    {
        return [
            'الرئيسية' => route('filament.admin.pages.dashboard'),
            'كوبونات الخصم' => CouponResource::getUrl('index'),
            'إضافة كوبون',
        ];
    }

    protected function getRedirectUrl(): string
    {
        return CouponResource::getUrl('edit', ['record' => $this->record]);
    }

    protected function getCreatedNotificationTitle(): ?string
    {
        return 'تم إنشاء الكوبون بنجاح';
    }

    protected function getCreatedNotification(): ?Notification
    {
        $code = strtoupper($this->record->code ?? '');

        return Notification::make()
            ->success()
            ->title('تم إنشاء الكوبون')
            ->body("الكوبون \"{$code}\" جاهز للاستخدام في المتجر.")
            ->icon('heroicon-o-check-circle')
            ->duration(5000);
    }

    protected function getFormActions(): array
    {
        return [
            $this->getCreateFormAction()
                ->label('حفظ الكوبون')
                ->icon('heroicon-o-check'),

            $this->getCreateAnotherFormAction()
                ->label('حفظ وإضافة آخر')
                ->icon('heroicon-o-plus-circle'),

            $this->getCancelFormAction()
                ->label('إلغاء')
                ->icon('heroicon-o-x-mark'),
        ];
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['code'] = strtoupper(trim($data['code'] ?? ''));

        if (($data['type'] ?? null) === 'percentage' && isset($data['value'])) {
            $data['value'] = min(100, max(0, (float) $data['value']));
        }

        if (isset($data['used_count']) && ! is_null($data['used_count'])) {
            $data['used_count'] = max(0, (int) $data['used_count']);
        }

        return $data;
    }
}