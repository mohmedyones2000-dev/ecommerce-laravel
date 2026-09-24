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
        $this->command->info('→ إضافة تقييمات للوصول إلى 20 لكل منتج...');

        $products = Product::all();
        $customers = User::where('role', 'customer')->get();

        if ($customers->isEmpty() || $products->isEmpty()) {
            $this->command->error('   ✗ لا يوجد مستخدمون أو منتجات');
            return;
        }

        $targetReviewsPerProduct = 20;
        $customerIds = $customers->pluck('id')->toArray();

        $comments = [
            'منتج ممتاز وجودة عالية، أنصح به',
            'جيد جداً لكن المقاس صغير قليلاً',
            'رائع! وصل بسرعة والتغليف احترافي',
            'السعر مناسب للجودة الممتازة',
            'اللون مطابق للصورة تماماً',
            'خدمة العملاء ممتازة، شكراً',
            'المنتج مطابق للوصف تماماً',
            'جودة جيدة، سأشتري مرة أخرى',
            'خفيف ومريح جداً، أنصح به',
            'وصل في الوقت المحدد، تجربة رائعة',
            'قماش فاخر ومريح',
            'قيمة ممتازة مقابل السعر',
            'المنتج تجاوز توقعاتي',
            'تغليف احترافي وتسليم سريع',
            'شكل أنيق وجودة عالية',
            'راضي جداً بالشراء',
            'المنتج رائع بكل المقاييس',
            'خامة ممتازة ومتناسقة',
            'يستحق السعر فعلاً',
            'سأوصي به لأصدقائي',
            'التفاصيل دقيقة جداً',
            'تصميم عصري وأنيق',
            'الجودة تفوق التوقعات',
            'مقاس مضبوط تماماً',
            'ألوان جميلة وثابتة',
        ];

        $totalAdded = 0;

        foreach ($products as $product) {
            // عدد التقييمات الحالية
            $currentCount = $product->reviews()->count();

            if ($currentCount >= $targetReviewsPerProduct) {
                continue;
            }

            $needed = $targetReviewsPerProduct - $currentCount;

            // المستخدمون الذين قيّموا المنتج مسبقاً
            $existingReviewerIds = $product->reviews()->pluck('user_id')->toArray();

            // المرشحون الجدد (لم يقيّموا هذا المنتج)
            $availableReviewers = array_diff($customerIds, $existingReviewerIds);

            if (empty($availableReviewers)) {
                continue;
            }

            // خلط عشوائي
            shuffle($availableReviewers);

            // خذ العدد المطلوب
            $selectedReviewers = array_slice($availableReviewers, 0, $needed);

            foreach ($selectedReviewers as $userId) {
                // توزيع واقعي للنجوم: 5 (50%), 4 (35%), 3 (15%)
                $ratingRoll = rand(1, 100);
                $rating = match (true) {
                    $ratingRoll <= 50 => 5,
                    $ratingRoll <= 85 => 4,
                    default            => 3,
                };

                Review::create([
                    'user_id'    => $userId,
                    'product_id' => $product->id,
                    'rating'     => $rating,
                    'comment'    => $comments[array_rand($comments)],
                    'created_at' => now()->subDays(rand(1, 180)),
                    'updated_at' => now()->subDays(rand(0, 30)),
                ]);

                $totalAdded++;
            }
        }

        $this->command->line("   ✓ {$totalAdded} تقييم جديد أُضيف");
        $this->command->line('   ℹ المجموع الآن: ' . Review::count() . ' تقييم');
    }
}