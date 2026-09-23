<?php

namespace App\Filament\Resources\PageResource\Pages;

use App\Filament\Resources\PageResource;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

class EditPage extends EditRecord
{
    protected static string $resource = PageResource::class;

    public function getTitle(): string
    {
        return 'تعديل الصفحة: ' . ($this->record->title ?? '');
    }

    public function getSubheading(): ?string
    {
        $status = $this->record->is_active ? 'نشطة' : 'غير نشطة';
        $footer = $this->record->show_in_footer ? 'تظهر في الفوتر' : 'مخفية من الفوتر';
        $slug = $this->record->slug ?? '—';

        return "الحالة: {$status} • {$footer} • الرابط: /{$slug}";
    }

    public function getBreadcrumbs(): array
    {
        return [
            'الرئيسية' => route('filament.admin.pages.dashboard'),
            'الصفحات الثابتة' => PageResource::getUrl('index'),
            'تعديل الصفحة',
        ];
    }

    protected function getRedirectUrl(): string
    {
        return PageResource::getUrl('edit', ['record' => $this->record]);
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
            ->body("تم تحديث الصفحة \"{$this->record->title}\" بنجاح.")
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
            Actions\Action::make('view_page')
                ->label('عرض في المتجر')
                ->icon('heroicon-o-arrow-top-right-on-square')
                ->color('gray')
                ->visible(fn (): bool => ! empty($this->record->slug))
                ->url(fn () => url('/page/' . $this->record->slug))
                ->openUrlInNewTab(),

            Actions\Action::make('toggle_active')
                ->label(fn (): string => $this->record->is_active ? 'تعطيل الصفحة' : 'تفعيل الصفحة')
                ->icon(fn (): string => $this->record->is_active
                    ? 'heroicon-o-x-circle'
                    : 'heroicon-o-check-circle')
                ->color(fn (): string => $this->record->is_active ? 'danger' : 'success')
                ->requiresConfirmation()
                ->modalHeading(fn (): string => $this->record->is_active
                    ? 'تعطيل الصفحة'
                    : 'تفعيل الصفحة')
                ->modalDescription(fn (): string => $this->record->is_active
                    ? 'لن تكون الصفحة مرئية للزوار بعد التعطيل.'
                    : 'ستكون الصفحة مرئية للزوار بعد التفعيل.')
                ->modalSubmitActionLabel(fn (): string => $this->record->is_active
                    ? 'نعم، عطّل'
                    : 'نعم، فعّل')
                ->action(function () {
                    $this->record->update(['is_active' => ! $this->record->is_active]);
                    $this->record->refresh();

                    Notification::make()
                        ->title($this->record->is_active ? 'تم تفعيل الصفحة' : 'تم تعطيل الصفحة')
                        ->success()
                        ->send();
                }),

            Actions\DeleteAction::make()
                ->label('حذف الصفحة')
                ->icon('heroicon-o-trash')
                ->requiresConfirmation()
                ->modalHeading('حذف الصفحة')
                ->modalDescription(fn (): string =>
                    "هل أنت متأكد من حذف الصفحة \"{$this->record->title}\"؟ لا يمكن التراجع.")
                ->modalSubmitActionLabel('نعم، احذف'),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        if (empty($data['slug']) && ! empty($data['title'])) {
            $data['slug'] = \Illuminate\Support\Str::slug($data['title']);
        }

        return $data;
    }

    protected function afterSave(): void
    {
        $this->record->refresh();
    }
}