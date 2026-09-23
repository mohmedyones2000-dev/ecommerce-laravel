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
            // ✅ 3-5 صور لكل منتج
            $imageCount = rand(3, 5);

            for ($i = 0; $i < $imageCount; $i++) {
                ProductImage::create([
                    'product_id' => $product->id,
                    'image_path' => 'https://picsum.photos/seed/' . $product->id . '-' . $i . '/600/800',
                    'is_primary' => $i === 0,
                    'color'      => null,
                    'sort_order' => $i,
                ]);
            }
        }

        $this->command->info('✅ Product images seeded: ' . ProductImage::count() . ' images');
    }
}