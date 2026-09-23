<?php

namespace App\Filament\Resources\AddressResource\Pages;

use App\Filament\Resources\AddressResource;
use App\Filament\Resources\UserResource;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

class EditAddress extends EditRecord
{
    protected static string $resource = AddressResource::class;

    public function getTitle(): string
    {
        return 'تفاصيل العنوان';
    }

    public function getSubheading(): ?string
    {
        $customer = $this->record->user?->name ?? 'غير معروف';
        $city = $this->record->city?->name ?? '—';
        $phone = $this->record->phone ?: '—';

        return "العميل: {$customer} • المدينة: {$city} • الهاتف: {$phone}";
    }

    public function getBreadcrumbs(): array
    {
        return [
            'الرئيسية' => route('filament.admin.pages.dashboard'),
            'العناوين' => AddressResource::getUrl('index'),
            'تفاصيل العنوان',
        ];
    }

    protected function getFormActions(): array
    {
        return [];
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('view_customer')
                ->label('عرض العميل')
                ->icon('heroicon-o-user')
                ->color('gray')
                ->visible(fn (): bool => $this->record->user !== null)
                ->url(fn () => UserResource::getUrl('edit', [
                    'record' => $this->record->user_id,
                ])),

            Actions\Action::make('view_city')
                ->label('عرض المدينة')
                ->icon('heroicon-o-map-pin')
                ->color('gray')
                ->visible(fn (): bool => $this->record->city !== null)
                ->url(fn () => \App\Filament\Resources\CityResource::getUrl('edit', [
                    'record' => $this->record->city_id,
                ])),

            Actions\DeleteAction::make()
                ->label('حذف العنوان')
                ->icon('heroicon-o-trash')
                ->requiresConfirmation()
                ->modalHeading('حذف العنوان')
                ->modalDescription(fn (): string =>
                    "هل أنت متأكد من حذف عنوان \"{$this->record->user?->name}\"؟ لا يمكن التراجع.")
                ->modalSubmitActionLabel('نعم، احذف')
                ->after(function () {
                    Notification::make()
                        ->title('تم حذف العنوان')
                        ->success()
                        ->send();
                }),
        ];
    }

    protected function afterSave(): void
    {
        $this->record->refresh();
    }
}