<?php

namespace Database\Seeders;

use App\Models\Color;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Database\Seeder;

class ProductVariantSeeder extends Seeder
{
    public function run(): void
    {
        $products = Product::all();
        $sizes = ['S', 'M', 'L', 'XL', 'XXL'];
        $shoeSizes = ['38', '39', '40', '41', '42', '43', '44'];
        $kidsSizes = ['2-3 سنوات', '4-5 سنوات', '6-7 سنوات', '8-9 سنوات'];

        $colors = Color::active()->get();

        foreach ($products as $product) {
            // ✅ تحديد المقاسات
            $availableSizes = $this->getSizesForProduct($product, $sizes, $shoeSizes, $kidsSizes);

            // ✅ اختيار 3-5 ألوان لكل منتج
            $productColors = $colors->random(min(rand(3, 5), $colors->count()));

            foreach ($productColors as $color) {
                foreach ($availableSizes as $size) {
                    ProductVariant::create([
                        'product_id'     => $product->id,
                        'color'          => $color->name,
                        'hex_code'       => $color->hex_code,
                        'size'           => $size,
                        'stock_quantity' => rand(5, 50),
                        'price'          => $product->price,
                        'discount_price' => $product->discount_price,
                    ]);
                }
            }
        }

        $this->command->info('✅ Product variants seeded: ' . ProductVariant::count() . ' variants');
    }

    /**
     * ✅ تحديد المقاسات المناسبة (بدون gender)
     */
    private function getSizesForProduct($product, $clothingSizes, $shoeSizes, $kidsSizes): array
    {
        // ✅ أحذية
        if (str_contains($product->name, 'حذاء')) {
            return array_slice($shoeSizes, 0, rand(3, 5));
        }

        // ✅ أطفال
        if (str_contains($product->name, 'أولادي') || 
            str_contains($product->name, 'بناتي') || 
            str_contains($product->name, 'حديثي الولادة') ||
            str_contains($product->name, 'أطفال')) {
            return array_slice($kidsSizes, 0, rand(2, 4));
        }

        // ✅ إكسسوارات (مقاس واحد)
        if (str_contains($product->name, 'حقيبة') || 
            str_contains($product->name, 'حزام') ||
            str_contains($product->name, 'قبعة') || 
            str_contains($product->name, 'ساعة')) {
            return ['One Size'];
        }

        // ✅ ملابس عادية
        return array_slice($clothingSizes, 0, rand(3, 5));
    }
}