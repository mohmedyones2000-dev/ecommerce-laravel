<?php

namespace App\Filament\Resources\ProductResource\Pages;

use App\Filament\Resources\ProductResource;
use App\Models\CartItem;
use App\Models\Color;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\DB;

class EditProduct extends EditRecord
{
    protected static string $resource = ProductResource::class;

    protected array $colorGroups = [];

    protected array $keptVariantIds = [];

    protected array $deletedVariants = [];

    public function getTitle(): string
    {
        return 'تعديل المنتج: ' . ($this->record->name ?? '');
    }

    public function getBreadcrumbs(): array
    {
        return [
            'الرئيسية' => route('filament.admin.pages.dashboard'),
            'المنتجات' => ProductResource::getUrl('index'),
            'تعديل',
        ];
    }

    protected function getRedirectUrl(): string
    {
        return ProductResource::getUrl('edit', ['record' => $this->record]);
    }

    protected function getSavedNotificationTitle(): ?string
    {
        return 'تم حفظ التعديلات بنجاح';
    }

    protected function getSavedNotification(): ?Notification
    {
        $body = "تم حفظ التعديلات على المنتج \"{$this->record->name}\".";

        if (! empty($this->deletedVariants)) {
            $count = count($this->deletedVariants);
            $body .= " تم حذف {$count} من المتغيرات لعدم وجودها في النموذج.";
        }

        return Notification::make()
            ->success()
            ->title('تم حفظ التعديلات')
            ->body($body)
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
                ->label('حذف المنتج')
                ->icon('heroicon-o-trash'),

            Actions\Action::make('view_in_store')
                ->label('عرض في المتجر')
                ->icon('heroicon-o-arrow-top-right-on-square')
                ->url(fn () => url('/product/' . $this->record->slug))
                ->openUrlInNewTab()
                ->color('gray'),
        ];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $variants = $this->record->variants()->orderBy('id')->get();

        $data['color_groups'] = $variants
            ->groupBy('color')
            ->map(function ($items, $color) {
                return [
                    'color'    => $color,
                    'hex_code' => $items->first()->hex_code,
                    'sizes'    => $items->map(fn ($v) => [
                        'size'           => $v->size,
                        'stock_quantity' => $v->stock_quantity,
                    ])->values()->toArray(),
                ];
            })
            ->values()
            ->toArray();

        return $data;
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $this->colorGroups = $data['color_groups'] ?? [];

        unset($data['color_groups']);

        return $data;
    }

    protected function afterSave(): void
    {
        DB::transaction(function () {
            $this->syncVariants();
            $this->deleteRemovedVariants();
        });

        $this->record->refresh();
    }

    protected function syncVariants(): void
    {
        $product = $this->record;
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

                $variant = $product->variants()
                    ->where('color', $colorName)
                    ->where('size', $sizeName)
                    ->first();

                if ($variant) {
                    $variant->update([
                        'hex_code'       => $hexCode,
                        'stock_quantity' => max(0, (int) ($size['stock_quantity'] ?? 0)),
                    ]);

                    $this->keptVariantIds[] = $variant->id;
                } else {
                    $new = $product->variants()->create([
                        'color'          => $colorName,
                        'hex_code'       => $hexCode,
                        'size'           => $sizeName,
                        'stock_quantity' => max(0, (int) ($size['stock_quantity'] ?? 0)),
                    ]);

                    $this->keptVariantIds[] = $new->id;
                }
            }
        }
    }

    protected function deleteRemovedVariants(): void
    {
        $product = $this->record;

        $toDelete = $product->variants()
            ->whereNotIn('id', $this->keptVariantIds)
            ->pluck('id')
            ->toArray();

        if (empty($toDelete)) {
            return;
        }

        $this->deletedVariants = $toDelete;

        CartItem::whereIn('product_variant_id', $toDelete)->delete();

        $product->variants()->whereIn('id', $toDelete)->delete();
    }
}