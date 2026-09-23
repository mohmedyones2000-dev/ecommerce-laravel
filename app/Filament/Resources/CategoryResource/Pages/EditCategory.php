<?php

namespace App\Filament\Resources\CategoryResource\Pages;

use App\Filament\Resources\CategoryResource;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

class EditCategory extends EditRecord
{
    protected static string $resource = CategoryResource::class;

    public function getTitle(): string
    {
        return 'تعديل التصنيف: ' . ($this->record->name ?? '');
    }

    public function getSubheading(): ?string
    {
        $productsCount = $this->record->products()->count();
        $subCategoriesCount = $this->record->subCategories()->count();

        return "المنتجات: {$productsCount} • التصنيفات الفرعية: {$subCategoriesCount}";
    }

    public function getBreadcrumbs(): array
    {
        return [
            'الرئيسية' => route('filament.admin.pages.dashboard'),
            'التصنيفات الرئيسية' => CategoryResource::getUrl('index'),
            'تعديل التصنيف',
        ];
    }

    protected function getRedirectUrl(): string
    {
        return CategoryResource::getUrl('edit', ['record' => $this->record]);
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
            ->body("تم تحديث التصنيف \"{$this->record->name}\" بنجاح.")
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
                ->label('حذف التصنيف')
                ->icon('heroicon-o-trash')
                ->requiresConfirmation()
                ->modalHeading('حذف التصنيف')
                ->modalDescription(fn (): string =>
                    $this->record->products()->count() > 0
                        ? "تحذير: هذا التصنيف يحتوي على " . $this->record->products()->count() . " منتج. سيتم فصلها عن التصنيف."
                        : 'هل أنت متأكد من حذف هذا التصنيف؟ لا يمكن التراجع.')
                ->modalSubmitActionLabel('نعم، احذف'),
        ];
    }

    protected function afterSave(): void
    {
        $this->record->refresh();
    }
}