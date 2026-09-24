<?php

namespace Database\Seeders;

use App\Models\Color;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class ProductVariantSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('→ إضافة variants للمنتجات التي بلا variants...');

        $products = Product::doesntHave('variants')->get();

        if ($products->isEmpty()) {
            $this->command->line('   ℹ جميع المنتجات لديها variants بالفعل');
            return;
        }

        // جلب الألوان (مع احتياطي لو scope active غير موجود)
        $colors = method_exists(Color::class, 'scopeActive')
            ? Color::active()->get()
            : Color::all();

        if ($colors->isEmpty()) {
            $this->command->error('   ✗ لا توجد ألوان');
            return;
        }

        $clothingSizes = ['S', 'M', 'L', 'XL', 'XXL'];
        $shoeSizes = ['38', '39', '40', '41', '42', '43', '44'];
        $kidsSizes = ['2-3 سنوات', '4-5 سنوات', '6-7 سنوات', '8-9 سنوات'];

        $hasHex = Schema::hasColumn('product_variants', 'hex_code');
        $hasPrice = Schema::hasColumn('product_variants', 'price');
        $hasDiscount = Schema::hasColumn('product_variants', 'discount_price');

        $variantsCreated = 0;

        foreach ($products as $product) {
            $sizes = $this->getSizesForProduct($product, $clothingSizes, $shoeSizes, $kidsSizes);
            $productColors = $colors->random(min(rand(2, 4), $colors->count()));

            foreach ($productColors as $color) {
                foreach ($sizes as $size) {
                    $data = [
                        'product_id'     => $product->id,
                        'color'          => $color->name,
                        'size'           => $size,
                        'stock_quantity' => rand(5, 40),
                    ];

                    if ($hasHex) $data['hex_code'] = $color->hex_code;
                    if ($hasPrice) $data['price'] = $product->price;
                    if ($hasDiscount) $data['discount_price'] = $product->discount_price;

                    ProductVariant::create($data);
                    $variantsCreated++;
                }
            }
        }

        $this->command->line("   ✓ {$variantsCreated} متغير جديد لـ {$products->count()} منتج");
        $this->command->line('   ℹ المجموع الآن: ' . ProductVariant::count() . ' متغير');
    }

    private function getSizesForProduct($product, $clothing, $shoes, $kids): array
    {
        $name = $product->name;

        if (str_contains($name, 'حذاء')) {
            return array_slice($shoes, 0, rand(3, 5));
        }
        if (str_contains($name, 'أولادي') || str_contains($name, 'بناتي') ||
            str_contains($name, 'حديثي الولادة') || str_contains($name, 'أطفال') ||
            str_contains($name, 'بيبي')) {
            return array_slice($kids, 0, rand(2, 4));
        }
        if (str_contains($name, 'حقيبة') || str_contains($name, 'حزام') ||
            str_contains($name, 'قبعة') || str_contains($name, 'ساعة') ||
            str_contains($name, 'محفظة') || str_contains($name, 'نظارة') ||
            str_contains($name, 'سكارف')) {
            return ['One Size'];
        }
        return array_slice($clothing, 0, rand(3, 5));
    }
}