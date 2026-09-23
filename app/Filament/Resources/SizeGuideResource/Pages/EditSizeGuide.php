<?php

namespace App\Filament\Resources\SizeGuideResource\Pages;

use App\Filament\Resources\SizeGuideResource;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

class EditSizeGuide extends EditRecord
{
    protected static string $resource = SizeGuideResource::class;

    public function getTitle(): string
    {
        return 'تعديل دليل المقاسات: ' . ($this->record->name ?? '');
    }

    public function getSubheading(): ?string
    {
        $itemsCount = $this->record->items()->count();
        $productsCount = $this->record->products()->count();
        $status = $this->record->is_active ? 'نشط' : 'غير نشط';

        return "الحالة: {$status} • المقاسات: {$itemsCount} • المنتجات المرتبطة: {$productsCount}";
    }

    public function getBreadcrumbs(): array
    {
        return [
            'الرئيسية' => route('filament.admin.pages.dashboard'),
            'أدلة المقاسات' => SizeGuideResource::getUrl('index'),
            'تعديل دليل المقاسات',
        ];
    }

    protected function getRedirectUrl(): string
    {
        return SizeGuideResource::getUrl('edit', ['record' => $this->record]);
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
            ->body("تم تحديث دليل المقاسات \"{$this->record->name}\" بنجاح.")
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
            Actions\Action::make('toggle_active')
                ->label(fn (): string => $this->record->is_active ? 'تعطيل الدليل' : 'تفعيل الدليل')
                ->icon(fn (): string => $this->record->is_active
                    ? 'heroicon-o-x-circle'
                    : 'heroicon-o-check-circle')
                ->color(fn (): string => $this->record->is_active ? 'danger' : 'success')
                ->requiresConfirmation()
                ->modalHeading(fn (): string => $this->record->is_active
                    ? 'تعطيل دليل المقاسات'
                    : 'تفعيل دليل المقاسات')
                ->modalDescription(fn (): string => $this->record->is_active
                    ? 'لن يظهر هذا الدليل في المتجر بعد التعطيل.'
                    : 'سيظهر هذا الدليل في المتجر بعد التفعيل.')
                ->modalSubmitActionLabel(fn (): string => $this->record->is_active
                    ? 'نعم، عطّل'
                    : 'نعم، فعّل')
                ->action(function () {
                    $this->record->update(['is_active' => ! $this->record->is_active]);
                    $this->record->refresh();

                    Notification::make()
                        ->title($this->record->is_active ? 'تم تفعيل الدليل' : 'تم تعطيل الدليل')
                        ->success()
                        ->send();
                }),

            Actions\DeleteAction::make()
                ->label('حذف الدليل')
                ->icon('heroicon-o-trash')
                ->requiresConfirmation()
                ->modalHeading('حذف دليل المقاسات')
                ->modalDescription(fn (): string =>
                    $this->record->products()->count() > 0
                        ? "تحذير: هذا الدليل مرتبط بـ " . $this->record->products()->count() . " منتج. سيتم فصلها عنه."
                        : 'هل أنت متأكد من حذف هذا الدليل؟ لا يمكن التراجع.')
                ->modalSubmitActionLabel('نعم، احذف'),
        ];
    }

    protected function afterSave(): void
    {
        $this->record->refresh();
    }
}