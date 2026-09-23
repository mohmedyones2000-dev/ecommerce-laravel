<?php

namespace App\Filament\Resources\ColorResource\Pages;

use App\Filament\Resources\ColorResource;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

class EditColor extends EditRecord
{
    protected static string $resource = ColorResource::class;

    public function getTitle(): string
    {
        return 'تعديل اللون: ' . ($this->record->name ?? '');
    }

    public function getSubheading(): ?string
    {
        $status = $this->record->is_active ? 'نشط' : 'غير نشط';
        $hex = $this->record->hex_code ?? '—';

        return "الحالة: {$status} • الكود: {$hex}";
    }

    public function getBreadcrumbs(): array
    {
        return [
            'الرئيسية' => route('filament.admin.pages.dashboard'),
            'الألوان' => ColorResource::getUrl('index'),
            'تعديل اللون',
        ];
    }

    protected function getRedirectUrl(): string
    {
        return ColorResource::getUrl('edit', ['record' => $this->record]);
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
            ->body("تم تحديث اللون \"{$this->record->name}\" بنجاح.")
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
                ->label(fn (): string => $this->record->is_active ? 'تعطيل اللون' : 'تفعيل اللون')
                ->icon(fn (): string => $this->record->is_active
                    ? 'heroicon-o-x-circle'
                    : 'heroicon-o-check-circle')
                ->color(fn (): string => $this->record->is_active ? 'danger' : 'success')
                ->requiresConfirmation()
                ->modalHeading(fn (): string => $this->record->is_active
                    ? 'تعطيل اللون'
                    : 'تفعيل اللون')
                ->modalDescription(fn (): string => $this->record->is_active
                    ? "لن يكون اللون \"{$this->record->name}\" متاحاً للاختيار في المنتجات بعد التعطيل."
                    : "سيصبح اللون \"{$this->record->name}\" متاحاً للاختيار في المنتجات.")
                ->modalSubmitActionLabel(fn (): string => $this->record->is_active
                    ? 'نعم، عطّل'
                    : 'نعم، فعّل')
                ->action(function () {
                    $this->record->update(['is_active' => ! $this->record->is_active]);
                    $this->record->refresh();
                    $this->fillForm();

                    Notification::make()
                        ->title($this->record->is_active ? 'تم تفعيل اللون' : 'تم تعطيل اللون')
                        ->success()
                        ->send();
                }),

            Actions\DeleteAction::make()
                ->label('حذف اللون')
                ->icon('heroicon-o-trash')
                ->requiresConfirmation()
                ->modalHeading('حذف اللون')
                ->modalDescription(fn (): string =>
                    "هل أنت متأكد من حذف اللون \"{$this->record->name}\"؟ لا يمكن التراجع.")
                ->modalSubmitActionLabel('نعم، احذف'),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $data['name'] = trim($data['name'] ?? '');

        if (! empty($data['hex_code'])) {
            $data['hex_code'] = strtoupper(trim($data['hex_code']));
        }

        return $data;
    }

    protected function afterSave(): void
    {
        $this->record->refresh();
    }
}