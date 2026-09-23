<?php

namespace App\Filament\Resources\CityResource\Pages;

use App\Filament\Resources\CityResource;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;

class CreateCity extends CreateRecord
{
    protected static string $resource = CityResource::class;

    public function getTitle(): string
    {
        return 'إضافة مدينة جديدة';
    }

    public function getBreadcrumbs(): array
    {
        return [
            'الرئيسية' => route('filament.admin.pages.dashboard'),
            'المدن' => CityResource::getUrl('index'),
            'إضافة مدينة',
        ];
    }

    protected function getRedirectUrl(): string
    {
        return CityResource::getUrl('edit', ['record' => $this->record]);
    }

    protected function getCreatedNotificationTitle(): ?string
    {
        return 'تم إنشاء المدينة بنجاح';
    }

    protected function getCreatedNotification(): ?Notification
    {
        $shipping = $this->record->is_free_shipping ? 'شحن مجاني' : 'شحن مدفوع';

        return Notification::make()
            ->success()
            ->title('تم إنشاء المدينة')
            ->body("المدينة \"{$this->record->name}\" أُضيفت بنجاح ({$shipping}).")
            ->icon('heroicon-o-check-circle')
            ->duration(5000);
    }

    protected function getFormActions(): array
    {
        return [
            $this->getCreateFormAction()
                ->label('حفظ المدينة')
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
        $data['name'] = trim($data['name'] ?? '');

        if (! empty($data['code'])) {
            $data['code'] = strtoupper(trim($data['code']));
        }

        if (! empty($data['is_free_shipping'])) {
            $data['shipping_cost'] = 0;
        }

        if (isset($data['shipping_cost'])) {
            $data['shipping_cost'] = max(0, (float) $data['shipping_cost']);
        }

        return $data;
    }
}