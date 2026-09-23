<?php

namespace App\Filament\Resources\ProductResource\Pages;

use App\Filament\Resources\ProductResource;
use App\Models\Color;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CreateProduct extends CreateRecord
{
    protected static string $resource = ProductResource::class;

    protected array $colorGroups = [];

    public function getTitle(): string
    {
        return 'إضافة منتج جديد';
    }

    public function getBreadcrumbs(): array
    {
        return [
            'الرئيسية' => route('filament.admin.pages.dashboard'),
            'المنتجات' => ProductResource::getUrl('index'),
            'إضافة منتج',
        ];
    }

    protected function getRedirectUrl(): string
    {
        return ProductResource::getUrl('edit', ['record' => $this->record]);
    }

    protected function getCreatedNotificationTitle(): ?string
    {
        return 'تم إنشاء المنتج بنجاح';
    }

    protected function getCreatedNotification(): ?Notification
    {
        return Notification::make()
            ->success()
            ->title('تم إنشاء المنتج')
            ->body("المنتج \"{$this->record->name}\" أُضيف بنجاح ويمكنك الآن إضافة باقي التفاصيل.")
            ->icon('heroicon-o-check-circle')
            ->duration(5000);
    }

    protected function getFormActions(): array
    {
        return [
            $this->getCreateFormAction()
                ->label('حفظ المنتج')
                ->icon('heroicon-o-check'),

            $this->getCreateAnotherFormAction()
                ->label('حفظ وإضافة آخر')
                ->icon('heroicon-o-plus-circle'),

            $this->getCancelFormAction()
                ->label('إلغاء')
                ->icon('heroicon-o-x-mark'),
        ];
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $this->colorGroups = $data['color_groups'] ?? [];

        unset($data['color_groups']);

        if (empty($data['slug']) && ! empty($data['name'])) {
            $data['slug'] = Str::slug($data['name']) . '-' . rand(100, 999);
        }

        return $data;
    }

    protected function afterCreate(): void
    {
        $this->createVariantsFromColorGroups();
    }

    protected function createVariantsFromColorGroups(): void
    {
        if (empty($this->colorGroups)) {
            return;
        }

        DB::transaction(function () {
            $seen = [];

            foreach ($this->colorGroups as $group) {
                $colorName = trim($group['color'] ?? '');

                if ($colorName === '') {
                    continue;
                }

                $hexCode = $group['hex_code'] ?? null;

                if (empty($hexCode)) {
                    $hexCode = Color::where('name', $colorName)->value('hex_code');
                }

                foreach ($group['sizes'] ?? [] as $size) {
                    $sizeName = trim($size['size'] ?? '');

                    if ($sizeName === '') {
                        continue;
                    }

                    $key = $colorName . '|' . $sizeName;

                    if (isset($seen[$key])) {
                        continue;
                    }

                    $seen[$key] = true;

                    $this->record->variants()->create([
                        'color'          => $colorName,
                        'hex_code'       => $hexCode,
                        'size'           => $sizeName,
                        'stock_quantity' => max(0, (int) ($size['stock_quantity'] ?? 0)),
                    ]);
                }
            }
        });

        $this->record->refresh();
    }
}