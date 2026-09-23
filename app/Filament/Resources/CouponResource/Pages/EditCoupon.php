<?php

namespace App\Filament\Resources\CouponResource\Pages;

use App\Filament\Resources\CouponResource;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

class EditCoupon extends EditRecord
{
    protected static string $resource = CouponResource::class;

    public function getTitle(): string
    {
        return 'تعديل الكوبون: ' . strtoupper($this->record->code ?? '');
    }

    public function getSubheading(): ?string
    {
        $status = $this->getStatusLabel();
        $used = $this->record->used_count ?? 0;
        $limit = $this->record->usage_limit ? ' / ' . $this->record->usage_limit : ' / ∞';
        $expires = $this->record->expires_at?->format('Y-m-d') ?? 'بدون انتهاء';

        return "الحالة: {$status} • الاستخدام: {$used}{$limit} • ينتهي: {$expires}";
    }

    public function getBreadcrumbs(): array
    {
        return [
            'الرئيسية' => route('filament.admin.pages.dashboard'),
            'كوبونات الخصم' => CouponResource::getUrl('index'),
            'تعديل الكوبون',
        ];
    }

    protected function getRedirectUrl(): string
    {
        return CouponResource::getUrl('edit', ['record' => $this->record]);
    }

    protected function getSavedNotificationTitle(): ?string
    {
        return 'تم حفظ التعديلات بنجاح';
    }

    protected function getSavedNotification(): ?Notification
    {
        $code = strtoupper($this->record->code ?? '');

        return Notification::make()
            ->success()
            ->title('تم حفظ التعديلات')
            ->body("تم تحديث الكوبون \"{$code}\" بنجاح.")
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
                ->label(fn (): string => $this->record->is_active ? 'تعطيل الكوبون' : 'تفعيل الكوبون')
                ->icon(fn (): string => $this->record->is_active
                    ? 'heroicon-o-x-circle'
                    : 'heroicon-o-check-circle')
                ->color(fn (): string => $this->record->is_active ? 'danger' : 'success')
                ->requiresConfirmation()
                ->modalHeading(fn (): string => $this->record->is_active
                    ? 'تعطيل الكوبون'
                    : 'تفعيل الكوبون')
                ->modalDescription(fn (): string => $this->record->is_active
                    ? "لن يعمل الكوبون \"{$this->record->code}\" في المتجر بعد التعطيل."
                    : "سيصبح الكوبون \"{$this->record->code}\" جاهزاً للاستخدام.")
                ->modalSubmitActionLabel(fn (): string => $this->record->is_active
                    ? 'نعم، عطّل'
                    : 'نعم، فعّل')
                ->action(function () {
                    $this->record->update(['is_active' => ! $this->record->is_active]);
                    $this->record->refresh();
                    $this->fillForm();

                    Notification::make()
                        ->title($this->record->is_active ? 'تم تفعيل الكوبون' : 'تم تعطيل الكوبون')
                        ->success()
                        ->send();
                }),

            Actions\Action::make('reset_usage')
                ->label('تصفير الاستخدام')
                ->icon('heroicon-o-arrow-path')
                ->color('warning')
                ->visible(fn (): bool => ($this->record->used_count ?? 0) > 0)
                ->requiresConfirmation()
                ->modalHeading('تصفير استخدام الكوبون')
                ->modalDescription(fn (): string =>
                    "سيتم تصفير عدد مرات استخدام \"{$this->record->code}\" إلى صفر. "
                    . 'هذا لا يؤثر على الطلبات السابقة.')
                ->modalSubmitActionLabel('نعم، صفّر')
                ->action(function () {
                    $this->record->update(['used_count' => 0]);
                    $this->record->refresh();
                    $this->fillForm();

                    Notification::make()
                        ->title('تم تصفير الاستخدام')
                        ->success()
                        ->send();
                }),

            Actions\DeleteAction::make()
                ->label('حذف الكوبون')
                ->icon('heroicon-o-trash')
                ->requiresConfirmation()
                ->modalHeading('حذف الكوبون')
                ->modalDescription(fn (): string =>
                    "هل أنت متأكد من حذف الكوبون \"{$this->record->code}\"؟ لا يمكن التراجع.")
                ->modalSubmitActionLabel('نعم، احذف'),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $data['code'] = strtoupper(trim($data['code'] ?? ''));

        if (($data['type'] ?? null) === 'percentage' && isset($data['value'])) {
            $data['value'] = min(100, max(0, (float) $data['value']));
        }

        return $data;
    }

    protected function afterSave(): void
    {
        $this->record->refresh();
    }

    protected function getStatusLabel(): string
    {
        if (! $this->record->is_active) {
            return 'معطل';
        }

        if ($this->record->expires_at && $this->record->expires_at->isPast()) {
            return 'منتهي';
        }

        if ($this->record->usage_limit && ($this->record->used_count ?? 0) >= $this->record->usage_limit) {
            return 'استُنفد';
        }

        return 'نشط';
    }
}