<?php

namespace App\Filament\Resources\SubCategoryResource\Pages;

use App\Filament\Resources\SubCategoryResource;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

class EditSubCategory extends EditRecord
{
    protected static string $resource = SubCategoryResource::class;

    public function getTitle(): string
    {
        return 'تعديل التصنيف الفرعي: ' . ($this->record->name ?? '');
    }

    public function getSubheading(): ?string
    {
        $productsCount = $this->record->products()->count();
        $categoriesCount = $this->record->categories()->count();

        return "المنتجات: {$productsCount} • التصنيفات الرئيسية: {$categoriesCount}";
    }

    public function getBreadcrumbs(): array
    {
        return [
            'الرئيسية' => route('filament.admin.pages.dashboard'),
            'التصنيفات الفرعية' => SubCategoryResource::getUrl('index'),
            'تعديل التصنيف الفرعي',
        ];
    }

    protected function getRedirectUrl(): string
    {
        return SubCategoryResource::getUrl('edit', ['record' => $this->record]);
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
            ->body("تم تحديث التصنيف الفرعي \"{$this->record->name}\" بنجاح.")
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
            Actions\DeleteAction::make()
                ->label('حذف التصنيف الفرعي')
                ->icon('heroicon-o-trash')
                ->requiresConfirmation()
                ->modalHeading('حذف التصنيف الفرعي')
                ->modalDescription(fn (): string =>
                    $this->record->products()->count() > 0
                        ? "تحذير: هذا التصنيف يحتوي على " . $this->record->products()->count() . " منتج. سيتم فصلها عن التصنيف."
                        : 'هل أنت متأكد من حذف هذا التصنيف الفرعي؟ لا يمكن التراجع.')
                ->modalSubmitActionLabel('نعم، احذف'),
        ];
    }

    protected function afterSave(): void
    {
        $this->record->refresh();
    }
}