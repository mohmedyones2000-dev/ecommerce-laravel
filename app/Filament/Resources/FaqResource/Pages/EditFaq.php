<?php

namespace App\Filament\Resources\FaqResource\Pages;

use App\Filament\Resources\FaqResource;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

class EditFaq extends EditRecord
{
    protected static string $resource = FaqResource::class;

    public function getTitle(): string
    {
        return 'تعديل السؤال';
    }

    public function getSubheading(): ?string
    {
        $status = $this->record->is_active ? 'نشط' : 'غير نشط';
        $order = $this->record->sort_order ?? 0;

        return "الحالة: {$status} • ترتيب العرض: {$order}";
    }

    public function getBreadcrumbs(): array
    {
        return [
            'الرئيسية' => route('filament.admin.pages.dashboard'),
            'الأسئلة الشائعة' => FaqResource::getUrl('index'),
            'تعديل السؤال',
        ];
    }

    protected function getRedirectUrl(): string
    {
        return FaqResource::getUrl('edit', ['record' => $this->record]);
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
            ->body('تم تحديث السؤال بنجاح.')
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
                ->label(fn (): string => $this->record->is_active ? 'تعطيل السؤال' : 'تفعيل السؤال')
                ->icon(fn (): string => $this->record->is_active
                    ? 'heroicon-o-x-circle'
                    : 'heroicon-o-check-circle')
                ->color(fn (): string => $this->record->is_active ? 'danger' : 'success')
                ->requiresConfirmation()
                ->modalHeading(fn (): string => $this->record->is_active
                    ? 'تعطيل السؤال'
                    : 'تفعيل السؤال')
                ->modalDescription(fn (): string => $this->record->is_active
                    ? 'لن يظهر هذا السؤال في المتجر بعد التعطيل.'
                    : 'سيظهر هذا السؤال في المتجر بعد التفعيل.')
                ->modalSubmitActionLabel(fn (): string => $this->record->is_active
                    ? 'نعم، عطّل'
                    : 'نعم، فعّل')
                ->action(function () {
                    $this->record->update(['is_active' => ! $this->record->is_active]);
                    $this->record->refresh();
                    $this->fillForm();

                    Notification::make()
                        ->title($this->record->is_active ? 'تم تفعيل السؤال' : 'تم تعطيل السؤال')
                        ->success()
                        ->send();
                }),

            Actions\DeleteAction::make()
                ->label('حذف السؤال')
                ->icon('heroicon-o-trash')
                ->requiresConfirmation()
                ->modalHeading('حذف السؤال')
                ->modalDescription('هل أنت متأكد من حذف هذا السؤال؟ لا يمكن التراجع.')
                ->modalSubmitActionLabel('نعم، احذف'),
        ];
    }

    protected function afterSave(): void
    {
        $this->record->refresh();
    }
}