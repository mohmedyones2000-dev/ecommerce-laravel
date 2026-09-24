<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class ProductImageSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('→ إضافة صور للمنتجات التي بلا صور...');

        $products = Product::doesntHave('images')->get();

        if ($products->isEmpty()) {
            $this->command->line('   ℹ جميع المنتجات لديها صور بالفعل');
            return;
        }

        $hasColor = Schema::hasColumn('product_images', 'color');
        $hasSort = Schema::hasColumn('product_images', 'sort_order');

        $imagesCreated = 0;

        foreach ($products as $product) {
            $imageCount = rand(3, 5);

            for ($i = 0; $i < $imageCount; $i++) {
                $data = [
                    'product_id' => $product->id,
                    'image_path' => 'data:image/svg+xml;base64,' . base64_encode(
                        $this->generateSvg($product->name, $i + 1)
                    ),
                    'is_primary' => $i === 0,
                ];

                if ($hasColor) $data['color'] = null;
                if ($hasSort) $data['sort_order'] = $i;

                ProductImage::create($data);
                $imagesCreated++;
            }
        }

        $this->command->line("   ✓ {$imagesCreated} صورة أُضيفت لـ {$products->count()} منتج");
        $this->command->line('   ℹ المجموع الآن: ' . ProductImage::count() . ' صورة');
    }

    /**
     * SVG أنيق يعرض اسم المنتج ورقم الصورة
     */
    private function generateSvg(string $productName, int $index): string
    {
        $shortName = mb_substr($productName, 0, 25);

        $palettes = [
            ['bg' => '#E8F0F8', 'shape' => '#005a96', 'accent' => '#14b8a6'],
            ['bg' => '#FEF3C7', 'shape' => '#D97706', 'accent' => '#F59E0B'],
            ['bg' => '#DBEAFE', 'shape' => '#3B82F6', 'accent' => '#60A5FA'],
            ['bg' => '#FCE7F3', 'shape' => '#EC4899', 'accent' => '#F472B6'],
            ['bg' => '#D1FAE5', 'shape' => '#10B981', 'accent' => '#34D399'],
            ['bg' => '#E0E7FF', 'shape' => '#6366F1', 'accent' => '#818CF8'],
            ['bg' => '#FEE2E2', 'shape' => '#DC2626', 'accent' => '#F87171'],
            ['bg' => '#F3E8FF', 'shape' => '#A855F7', 'accent' => '#C084FC'],
            ['bg' => '#CCFBF1', 'shape' => '#14B8A6', 'accent' => '#5EEAD4'],
            ['bg' => '#FEF9C3', 'shape' => '#CA8A04', 'accent' => '#FACC15'],
        ];

        $c = $palettes[($index - 1) % count($palettes)];

        // شكل هندسي مختلف لكل صورة
        $shapes = [
            // دائرة + مربع
            '<circle cx="300" cy="220" r="70" fill="' . $c['shape'] . '"/>'
            . '<rect x="180" y="320" width="240" height="200" rx="12" fill="' . $c['accent'] . '" opacity="0.7"/>',

            // مثلث
            '<path d="M300 150 L420 350 L180 350 Z" fill="' . $c['shape'] . '"/>'
            . '<rect x="230" y="400" width="140" height="60" rx="8" fill="' . $c['accent'] . '" opacity="0.7"/>',

            // مستطيلات متداخلة
            '<rect x="180" y="180" width="240" height="240" rx="16" fill="' . $c['shape'] . '"/>'
            . '<rect x="230" y="230" width="140" height="140" rx="12" fill="' . $c['accent'] . '"/>',

            // معين
            '<path d="M300 140 L440 280 L300 420 L160 280 Z" fill="' . $c['shape'] . '"/>'
            . '<circle cx="300" cy="280" r="40" fill="' . $c['accent'] . '"/>',

            // قوس
            '<path d="M180 380 Q180 180 300 180 Q420 180 420 380" stroke="' . $c['shape'] . '" stroke-width="60" fill="none" stroke-linecap="round"/>'
            . '<circle cx="300" cy="420" r="30" fill="' . $c['accent'] . '"/>',
        ];

        $shape = $shapes[($index - 1) % count($shapes)];

        return <<<SVG
<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 600 800" fill="none">
    <rect width="600" height="800" fill="{$c['bg']}"/>
    <rect x="60" y="100" width="480" height="600" rx="20" fill="white" opacity="0.5"/>
    {$shape}
    <text x="300" y="620" font-family="Cairo, Arial, sans-serif" font-size="26" font-weight="bold" fill="#0f0f0f" text-anchor="middle">{$shortName}</text>
    <text x="300" y="660" font-family="Cairo, Arial, sans-serif" font-size="16" fill="#6b6b5e" text-anchor="middle">صورة {$index}</text>
</svg>
SVG;
    }
}