<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Database\Seeder;

class ProductImageSeeder extends Seeder
{
    public function run(): void
    {
        $products = Product::all();

        foreach ($products as $product) {
            // ✅ 3-5 صور SVG مضمّنة (تعمل بدون إنترنت)
            $imageCount = rand(3, 5);

            for ($i = 0; $i < $imageCount; $i++) {
                ProductImage::create([
                    'product_id' => $product->id,
                    'image_path' => 'data:image/svg+xml;base64,' . base64_encode(
                        $this->generateSvg($product->name, $i + 1)
                    ),
                    'is_primary' => $i === 0,
                    'color'      => null,
                    'sort_order' => $i,
                ]);
            }
        }

        $this->command->info('✅ Product images seeded: ' . ProductImage::count() . ' images');
    }

    /**
     * ✅ SVG جميل يعرض اسم المنتج
     */
    private function generateSvg(string $productName, int $index): string
    {
        $shortName = mb_substr($productName, 0, 25);
        $colors = [
            ['bg' => '#F3F4F6', 'shape' => '#94A3B8'],
            ['bg' => '#FEF3C7', 'shape' => '#D97706'],
            ['bg' => '#DBEAFE', 'shape' => '#3B82F6'],
            ['bg' => '#FCE7F3', 'shape' => '#EC4899'],
            ['bg' => '#D1FAE5', 'shape' => '#10B981'],
            ['bg' => '#E0E7FF', 'shape' => '#6366F1'],
        ];
        $color = $colors[($index - 1) % count($colors)];

        return <<<SVG
<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 600 800" fill="none">
    <rect width="600" height="800" fill="{$color['bg']}"/>
    <rect x="80" y="120" width="440" height="560" rx="24" fill="white" opacity="0.6"/>
    <path d="M300 280 L200 360 L200 520 L400 520 L400 360 Z" fill="{$color['shape']}"/>
    <circle cx="300" cy="220" r="60" fill="{$color['shape']}"/>
    <text x="300" y="620" font-family="Arial, sans-serif" font-size="26" font-weight="bold" fill="#1F2937" text-anchor="middle">{$shortName}</text>
    <text x="300" y="660" font-family="Arial, sans-serif" font-size="18" fill="#6B7280" text-anchor="middle">صورة {$index}</text>
</svg>
SVG;
    }
}