<?php

namespace App\Filament\Resources\NewsletterSubscriberResource\Pages;

use App\Filament\Resources\NewsletterSubscriberResource;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

class EditNewsletterSubscriber extends EditRecord
{
    protected static string $resource = NewsletterSubscriberResource::class;

    public function getTitle(): string
    {
        return 'تعديل المشترك: ' . ($this->record->email ?? '');
    }

    public function getSubheading(): ?string
    {
        $status = $this->record->is_active ? 'نشط' : 'غير نشط';
        $subscribed = $this->record->subscribed_at?->format('Y-m-d') ?? '—';
        $unsubscribed = $this->record->unsubscribed_at?->format('Y-m-d') ?? '—';

        return "الحالة: {$status} • اشترك: {$subscribed} • ألغى: {$unsubscribed}";
    }

    public function getBreadcrumbs(): array
    {
        return [
            'الرئيسية' => route('filament.admin.pages.dashboard'),
            'المشتركون في النشرة' => NewsletterSubscriberResource::getUrl('index'),
            'تعديل المشترك',
        ];
    }

    protected function getRedirectUrl(): string
    {
        return NewsletterSubscriberResource::getUrl('edit', ['record' => $this->record]);
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
            ->body("تم تحديث بيانات المشترك \"{$this->record->email}\" بنجاح.")
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
                ->label(fn (): string => $this->record->is_active ? 'تعطيل الاشتراك' : 'تفعيل الاشتراك')
                ->icon(fn (): string => $this->record->is_active
                    ? 'heroicon-o-x-circle'
                    : 'heroicon-o-check-circle')
                ->color(fn (): string => $this->record->is_active ? 'danger' : 'success')
                ->requiresConfirmation()
                ->modalHeading(fn (): string => $this->record->is_active
                    ? 'تعطيل الاشتراك'
                    : 'تفعيل الاشتراك')
                ->modalDescription(fn (): string => $this->record->is_active
                    ? "لن يستلم \"{$this->record->email}\" رسائل النشرة بعد التعطيل."
                    : "سيبدأ \"{$this->record->email}\" باستلام رسائل النشرة.")
                ->modalSubmitActionLabel(fn (): string => $this->record->is_active
                    ? 'نعم، عطّل'
                    : 'نعم، فعّل')
                ->action(function () {
                    $isActive = ! $this->record->is_active;

                    $this->record->update([
                        'is_active'       => $isActive,
                        'unsubscribed_at' => $isActive ? null : now(),
                    ]);

                    $this->record->refresh();
                    $this->fillForm();

                    Notification::make()
                        ->title($isActive ? 'تم تفعيل الاشتراك' : 'تم تعطيل الاشتراك')
                        ->success()
                        ->send();
                }),

            Actions\DeleteAction::make()
                ->label('حذف المشترك')
                ->icon('heroicon-o-trash')
                ->requiresConfirmation()
                ->modalHeading('حذف المشترك')
                ->modalDescription(fn (): string =>
                    "هل أنت متأكد من حذف المشترك \"{$this->record->email}\"؟ لا يمكن التراجع.")
                ->modalSubmitActionLabel('نعم، احذف'),
        ];
    }

    protected function afterSave(): void
    {
        $this->record->refresh();
    }
}