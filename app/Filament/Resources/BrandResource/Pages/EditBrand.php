<?php

namespace App\Filament\Resources\BrandResource\Pages;

use App\Filament\Resources\BrandResource;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

class EditBrand extends EditRecord
{
    protected static string $resource = BrandResource::class;

    public function getTitle(): string
    {
        return 'تعديل العلامة: ' . ($this->record->name ?? '');
    }

    public function getSubheading(): ?string
    {
        $productsCount = $this->record->products()->count();
        $logoStatus = $this->record->logo ? 'لديها شعار' : 'بدون شعار';

        return "المنتجات المرتبطة: {$productsCount} • {$logoStatus}";
    }

    public function getBreadcrumbs(): array
    {
        return [
            'الرئيسية' => route('filament.admin.pages.dashboard'),
            'العلامات التجارية' => BrandResource::getUrl('index'),
            'تعديل العلامة',
        ];
    }

    protected function getRedirectUrl(): string
    {
        return BrandResource::getUrl('edit', ['record' => $this->record]);
    }

    protected function getSavedNotificationTitle(): ?string
    {
        return 'تم حفظ التعديلات بنجاح';
    }

    protected function getSavedNotification(): ?Notification
    {
        return Notification::make()
            ->success()
            ->title('تم حفظ التعديلات')
            ->body("تم تحديث العلامة التجارية \"{$this->record->name}\" بنجاح.")
            ->icon('heroicon-o-check-circle')
            ->duration(5000);
    }

    protected function getFormActions(): array
    {
        return [
            $this->getSaveFormAction()
                ->label('حفظ التعديلات')
                ->icon('heroicon-o-check'),

            $this->getCancelFormAction()
                ->label('إلغاء')
                ->icon('heroicon-o-x-mark'),
        ];
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('view_products')
                ->label('عرض المنتجات')
                ->icon('heroicon-o-shopping-bag')
                ->color('gray')
                ->visible(fn (): bool => $this->record->products()->count() > 0)
                ->url(fn () => \App\Filament\Resources\ProductResource::getUrl('index', [
                    'tableFilters' => [
                        'brand' => ['value' => $this->record->id],
                    ],
                ])),

            Actions\DeleteAction::make()
                ->label('حذف العلامة')
                ->icon('heroicon-o-trash')
                ->requiresConfirmation()
                ->modalHeading('حذف العلامة التجارية')
                ->modalDescription(fn (): string =>
                    $this->record->products()->count() > 0
                        ? "تحذير: هذه العلامة مرتبطة بـ " . $this->record->products()->count() . " منتج. سيتم فصلها عنهم."
                        : "هل أنت متأكد من حذف العلامة \"{$this->record->name}\"؟ لا يمكن التراجع.")
                ->modalSubmitActionLabel('نعم، احذف'),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $data['name'] = trim($data['name'] ?? '');

        return $data;
    }

    protected function afterSave(): void
    {
        $this->record->refresh();
    }
}