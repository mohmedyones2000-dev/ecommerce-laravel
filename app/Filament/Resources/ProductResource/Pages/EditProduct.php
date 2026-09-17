<?php

namespace App\Filament\Resources\ProductResource\Pages;

use App\Filament\Resources\ProductResource;
use App\Models\CartItem;
use Filament\Resources\Pages\EditRecord;

class EditProduct extends EditRecord
{
    protected static string $resource = ProductResource::class;

    protected array $colorGroups = [];

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
        $product = $this->record;
        $keptVariantIds = [];

        foreach ($this->colorGroups as $group) {
            $colorName = $group['color'] ?? null;

            if (!$colorName) {
                continue;
            }

            foreach ($group['sizes'] ?? [] as $size) {
                if (empty($size['size'])) {
                    continue;
                }

                $variant = $product->variants()
                    ->where('color', $colorName)
                    ->where('size', $size['size'])
                    ->first();

                if ($variant) {
                    $variant->update([
                        'hex_code'       => $group['hex_code'] ?? null,
                        'stock_quantity' => (int) ($size['stock_quantity'] ?? 0),
                    ]);

                    $keptVariantIds[] = $variant->id;
                } else {
                    $new = $product->variants()->create([
                        'color'          => $colorName,
                        'hex_code'       => $group['hex_code'] ?? null,
                        'size'           => $size['size'],
                        'stock_quantity' => (int) ($size['stock_quantity'] ?? 0),
                    ]);

                    $keptVariantIds[] = $new->id;
                }
            }
        }

        $toDelete = $product->variants()
            ->whereNotIn('id', $keptVariantIds)
            ->pluck('id')
            ->toArray();

        if (!empty($toDelete)) {
            CartItem::whereIn('product_variant_id', $toDelete)->delete();

            $product->variants()->whereIn('id', $toDelete)->delete();
        }
    }
}