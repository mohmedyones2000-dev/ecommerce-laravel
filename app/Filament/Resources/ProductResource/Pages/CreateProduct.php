<?php

namespace App\Filament\Resources\ProductResource\Pages;

use App\Filament\Resources\ProductResource;
use Filament\Resources\Pages\CreateRecord;

class CreateProduct extends CreateRecord
{
    protected static string $resource = ProductResource::class;

    protected array $colorGroups = [];

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $this->colorGroups = $data['color_groups'] ?? [];

        unset($data['color_groups']);

        return $data;
    }

    protected function afterCreate(): void
    {
        foreach ($this->colorGroups as $group) {
            $colorName = $group['color'] ?? null;

            if (!$colorName) {
                continue;
            }

            foreach ($group['sizes'] ?? [] as $size) {
                if (empty($size['size'])) {
                    continue;
                }

                $this->record->variants()->create([
                    'color'          => $colorName,
                    'hex_code'       => $group['hex_code'] ?? null,
                    'size'           => $size['size'],
                    'stock_quantity' => (int) ($size['stock_quantity'] ?? 0),
                ]);
            }
        }
    }
}