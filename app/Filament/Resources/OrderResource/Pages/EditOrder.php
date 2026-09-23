<?php

namespace App\Filament\Resources\OrderResource\Pages;

use App\Filament\Resources\OrderResource;
use App\Services\NotificationService;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

class EditOrder extends EditRecord
{
    protected static string $resource = OrderResource::class;

    protected ?string $originalStatus = null;

    protected ?string $originalPaymentStatus = null;

    public function getTitle(): string
    {
        return 'تعديل الطلب: ' . ($this->record->order_number ?? '');
    }

    public function getSubheading(): ?string
    {
        $customer = $this->record->user?->name ?? 'غير معروف';
        $total = number_format((float) $this->record->total_amount, 2);

        return "العميل: {$customer} • الإجمالي: \${$total}";
    }

    public function getBreadcrumbs(): array
    {
        return [
            'الرئيسية' => route('filament.admin.pages.dashboard'),
            'الطلبات' => OrderResource::getUrl('index'),
            'تعديل الطلب',
        ];
    }

    protected function getRedirectUrl(): string
    {
        return OrderResource::getUrl('edit', ['record' => $this->record]);
    }

    protected function getSavedNotificationTitle(): ?string
    {
        return 'تم حفظ التعديلات بنجاح';
    }

    protected function getSavedNotification(): ?Notification
    {
        $body = "تم تحديث الطلب #{$this->record->order_number}.";

        $notifications = [];

        if ($this->originalStatus !== $this->record->status) {
            $notifications[] = 'تم تحديث حالة الطلب';
        }

        if ($this->originalPaymentStatus !== $this->record->payment_status) {
            $notifications[] = 'تم تحديث حالة الدفع';
        }

        if (! empty($notifications)) {
            $body .= ' ' . implode(' و', $notifications) . '.';
        }

        return Notification::make()
            ->success()
            ->title('تم حفظ التعديلات')
            ->body($body)
            ->icon('heroicon-o-check-circle')
            ->duration(5000);
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('print_invoice')
                ->label('طباعة الفاتورة')
                ->icon('heroicon-o-printer')
                ->color('gray')
                ->url(fn () => url('/admin/orders/' . $this->record->id . '/invoice'))
                ->openUrlInNewTab(),

            Actions\Action::make('refresh')
                ->label('تحديث')
                ->icon('heroicon-o-arrow-path')
                ->color('gray')
                ->action(function () {
                    $this->record->refresh();
                    $this->fillForm();

                    Notification::make()
                        ->title('تم تحديث البيانات')
                        ->success()
                        ->send();
                }),

            Actions\Action::make('back')
                ->label('عودة للقائمة')
                ->icon('heroicon-o-arrow-right')
                ->color('gray')
                ->url(OrderResource::getUrl('index')),
        ];
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

    protected function beforeSave(): void
    {
        $this->record->refresh();

        $this->originalStatus = $this->record->getOriginal('status');
        $this->originalPaymentStatus = $this->record->getOriginal('payment_status');
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        unset(
            $data['order_number'],
            $data['user_id'],
            $data['total_amount'],
            $data['created_at'],
        );

        return $data;
    }

    protected function afterSave(): void
    {
        $order = $this->record;

        if ($this->originalStatus !== $order->status) {
            NotificationService::orderStatusChanged($order, $order->status);
        }

        $order->refresh();
    }
}