<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Review;
use App\Models\User;
use Illuminate\Database\Seeder;

class ReviewSeeder extends Seeder
{
    public function run(): void
    {
        $customers = User::where('role', 'customer')->get();
        $products = Product::all();

        if ($customers->isEmpty() || $products->isEmpty()) {
            return;
        }

        $comments = [
            'منتج ممتاز وجودة عالية، أنصح به',
            'جيد جداً لكن المقاس صغير قليلاً',
            'رائع! وصل بسرعة والتغليف احترافي',
            'السعر مناسب للجودة الممتازة',
            'لم يعجبني اللون، لكن القماش ممتاز',
            'خدمة العملاء ممتازة، شكراً',
            'المنتج مطابق للوصف تماماً',
            'جودة جيدة، سأشتري مرة أخرى',
            'خفيف ومريح جداً، أنصح به',
            'وصل في الوقت المحدد، تجربة رائعة',
        ];

        foreach ($products as $product) {
            // ✅ 2-4 تقييمات لكل منتج
            $reviewCount = rand(2, 4);
            $reviewers = $customers->random(min($reviewCount, $customers->count()));

            foreach ($reviewers as $customer) {
                // منع التكرار
                $exists = Review::where('user_id', $customer->id)
                    ->where('product_id', $product->id)
                    ->exists();

                if ($exists) continue;

                Review::create([
                    'user_id'    => $customer->id,
                    'product_id' => $product->id,
                    'rating'     => rand(3, 5),
                    'comment'    => $comments[array_rand($comments)],
                ]);
            }
        }

        $this->command->info('✅ Reviews seeded: ' . Review::count() . ' reviews');
    }
}