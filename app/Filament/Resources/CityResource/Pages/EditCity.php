<?php

namespace App\Filament\Resources\CityResource\Pages;

use App\Filament\Resources\CityResource;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

class EditCity extends EditRecord
{
    protected static string $resource = CityResource::class;

    public function getTitle(): string
    {
        return 'تعديل المدينة: ' . ($this->record->name ?? '');
    }

    public function getSubheading(): ?string
    {
        $shipping = $this->record->is_free_shipping
            ? 'شحن مجاني'
            : 'شحن مدفوع ($' . number_format((float) $this->record->shipping_cost, 2) . ')';

        $addresses = $this->record->addresses()->count();

        return "{$shipping} • العناوين المرتبطة: {$addresses}";
    }

    public function getBreadcrumbs(): array
    {
        return [
            'الرئيسية' => route('filament.admin.pages.dashboard'),
            'المدن' => CityResource::getUrl('index'),
            'تعديل المدينة',
        ];
    }

    protected function getRedirectUrl(): string
    {
        return CityResource::getUrl('edit', ['record' => $this->record]);
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
            ->body("تم تحديث المدينة \"{$this->record->name}\" بنجاح.")
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
            Actions\Action::make('toggle_shipping')
                ->label(fn (): string => $this->record->is_free_shipping
                    ? 'تحويل إلى شحن مدفوع'
                    : 'تحويل إلى شحن مجاني')
                ->icon(fn (): string => $this->record->is_free_shipping
                    ? 'heroicon-o-banknotes'
                    : 'heroicon-o-gift')
                ->color(fn (): string => $this->record->is_free_shipping
                    ? 'warning'
                    : 'success')
                ->requiresConfirmation()
                ->modalHeading(fn (): string => $this->record->is_free_shipping
                    ? 'تحويل إلى شحن مدفوع'
                    : 'تحويل إلى شحن مجاني')
                ->modalDescription(fn (): string => $this->record->is_free_shipping
                    ? "سيتم تفعيل الشحن المدفوع للمدينة \"{$this->record->name}\". لا تنسَ تعديل التكلفة إذا لزم."
                    : "سيصبح الشحن مجانياً للمدينة \"{$this->record->name}\".")
                ->modalSubmitActionLabel(fn (): string => $this->record->is_free_shipping
                    ? 'نعم، حوّل'
                    : 'نعم، فعّل')
                ->action(function () {
                    $isFree = ! $this->record->is_free_shipping;

                    $this->record->update([
                        'is_free_shipping' => $isFree,
                        'shipping_cost' => $isFree ? 0 : $this->record->shipping_cost,
                    ]);

                    $this->record->refresh();
                    $this->fillForm();

                    Notification::make()
                        ->title($isFree ? 'تم تفعيل الشحن المجاني' : 'تم تحويل المدينة إلى شحن مدفوع')
                        ->success()
                        ->send();
                }),

            Actions\DeleteAction::make()
                ->label('حذف المدينة')
                ->icon('heroicon-o-trash')
                ->requiresConfirmation()
                ->modalHeading('حذف المدينة')
                ->modalDescription(fn (): string =>
                    $this->record->addresses()->count() > 0
                        ? "تحذير: هذه المدينة مرتبطة بـ " . $this->record->addresses()->count() . " عنوان. لن تُحذف العناوين، لكن ستفقد الربط."
                        : "هل أنت متأكد من حذف المدينة \"{$this->record->name}\"؟ لا يمكن التراجع.")
                ->modalSubmitActionLabel('نعم، احذف'),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $data['name'] = trim($data['name'] ?? '');

        if (! empty($data['code'])) {
            $data['code'] = strtoupper(trim($data['code']));
        }

        if (! empty($data['is_free_shipping'])) {
            $data['shipping_cost'] = 0;
        }

        if (isset($data['shipping_cost'])) {
            $data['shipping_cost'] = max(0, (float) $data['shipping_cost']);
        }

        return $data;
    }

    protected function afterSave(): void
    {
        $this->record->refresh();
    }
}