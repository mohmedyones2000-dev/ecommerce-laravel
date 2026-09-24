<?php

namespace Database\Seeders;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Color;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductVariant;
use App\Models\SizeGuide;
use App\Models\SubCategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('→ إضافة 50 منتج كامل...');

        $categories = Category::all();
        $subCategories = SubCategory::all();
        $brands = Brand::all();
        $colors = Color::pluck('name')->toArray();
        $sizeGuides = SizeGuide::pluck('id', 'name')->toArray();

        if ($categories->isEmpty() || empty($colors)) {
            $this->command->error('   ✗ لا توجد تصنيفات أو ألوان');
            return;
        }

        $hasSlug = Schema::hasColumn('products', 'slug');
        $hasSizeGuide = Schema::hasColumn('products', 'size_guide_id');

        // 50 منتج جديد بأسماء متنوعة
        $productsData = [
            ['قميص أوكسفورد رجالي', 'قميص رسمي بقماش أوكسفورد فاخر، مثالي للعمل والمناسبات.', 180, 'men'],
            ['تيشيرت بولو كلاسيك', 'تيشيرت بولو بياقة كلاسيكية، قماش قطني عالي الجودة.', 120, 'men'],
            ['بنطال قماش رسمي', 'بنطال قماش رسمي بقصة مستقيمة، مناسب للدوام.', 200, 'men'],
            ['جاكيت بومبر رياضي', 'جاكيت بومبر خفيف بتصميم عصري، مناسب للربيع والخريف.', 320, 'men'],
            ['كارديجان صوف رجالي', 'كارديجان صوف دافئ بتصميم أنيق، مناسب للشتاء.', 280, 'men'],
            ['قميص كتان صيفي', 'قميص كتان خفيف ومريح، مثالي للصيف.', 160, 'men'],
            ['شورت رياضي مريح', 'شورت رياضي بقماش يسمح بالتهوية، مناسب للتمارين.', 90, 'men'],
            ['حذاء كاجوال جلد', 'حذاء كاجوال من الجلد الطبيعي بتصميم أنيق.', 420, 'men'],
            ['حذاء رسمي كلاسيك', 'حذاء رسمي كلاسيكي للبدلات والمناسبات.', 480, 'men'],
            ['محفظة جلد رجالية', 'محفظة جلدية أنيقة بجيوب متعددة.', 140, 'men'],

            ['فستان كاجوال قصير', 'فستان كاجوال بتصميم بسيط وأنيق، مناسب للخروج اليومي.', 280, 'women'],
            ['بلوزة شيفون مشغولة', 'بلوزة شيفون بتفاصيل أنيقة، مثالية للمناسبات.', 220, 'women'],
            ['بنطال جينز نسائي ضيق', 'بنطال جينز بقصة ضيقة، مرن ومريح.', 240, 'women'],
            ['جاكيت بليزر نسائي', 'بليزر نسائي بقصة أنيقة، مناسب للعمل.', 380, 'women'],
            ['كنزة صوف بياقة', 'كنزة صوف دافئة بياقة عالية، للشتاء.', 200, 'women'],
            ['عباية كلاسيك سادة', 'عباية كلاسيكية بتصميم بسيط وأنيق.', 350, 'women'],
            ['فستان زفاف بسيط', 'فستان زفاف بتصميم بسيط وأنيق.', 1500, 'women'],
            ['حقيبة كتف صغيرة', 'حقيبة كتف صغيرة بتصميم عصري.', 260, 'women'],
            ['حذاء كعب عالي', 'حذاء كعب عالي بتصميم أنيق للفترات الخاصة.', 340, 'women'],
            ['شال حرير مطبوع', 'شال حرير ناعم بطبعة أنيقة.', 120, 'women'],

            ['طقم أولادي رياضي', 'طقم رياضي للأولاد بقماش مريح.', 140, 'kids'],
            ['فستان بناتي كاجوال', 'فستان بناتي بطبعة مسلية.', 130, 'kids'],
            ['جاكيت أطفال شتوي', 'جاكيت شتوي دافئ للأطفال.', 200, 'kids'],
            ['طقم أطفال قطني', 'طقم قطني مريح للأطفال.', 110, 'kids'],
            ['حذاء أطفال رياض', 'حذاء رياضي خفيف ومريح.', 150, 'kids'],
            ['طقم بيبي 3 قطع', 'طقم بيبي ناعم من 3 قطع.', 130, 'kids'],
            ['قبعة أطفال شمس', 'قبعة بحماية من الشمس للأطفال.', 60, 'kids'],

            ['طقم رياضي رجالي', 'طقم رياضي كامل بقطعتين.', 300, 'unisex'],
            ['حذاء جري احترافي', 'حذاء جري بخفة عالية وامتصاص صدمات.', 450, 'unisex'],
            ['تيشيرت رياضي نايك', 'تيشيرت رياضي بقماش يمنع التعرق.', 120, 'unisex'],
            ['بنطال رياضي ضيق', 'بنطال رياضي مرن بقصة ضيقة.', 180, 'unisex'],
            ['جاكيت رياضي خفيف', 'جاكيت رياضي خفيف للماراثون.', 260, 'unisex'],
            ['كاب رياضي', 'كاب رياضي قابل للتعديل.', 70, 'unisex'],

            ['ساعة يد ذكية', 'ساعة ذكية بشاشة لمسية ومراقبة نبضات.', 650, 'unisex'],
            ['ساعة يد كلاسيكية', 'ساعة كلاسيكية بحزام جلد.', 550, 'men'],
            ['نظارة شمسية بولارايزد', 'نظارة شمسية بحماية UV400.', 280, 'unisex'],
            ['نظارة طبية إطار معدني', 'نظارة طبية بإطار معدني خفيف.', 200, 'unisex'],
            ['حقيبة ظهر مدرسية', 'حقيبة ظهر واسعة بجيوب متعددة.', 220, 'unisex'],
            ['حزام جلد عريض', 'حزام جلد عريض بإبزيم فضي.', 150, 'men'],
            ['سكارف صوف', 'سكارف صوف ناعم ودافئ.', 100, 'unisex'],
            ['قفازات شتوية', 'قفازات شتوية دافئة.', 80, 'unisex'],
            ['محفظة نسائية صغيرة', 'محفظة نسائية أنيقة وصغيرة.', 120, 'women'],
            ['حقيبة سفر متوسطة', 'حقيبة سفر متوسطة الحجم بعجلات.', 480, 'unisex'],
            ['حقيبة يد فاخرة', 'حقيبة يد فاخرة بتصميم راقٍ.', 850, 'women'],
            ['معطف شتوي طويل', 'معطف شتوي طويل بتصميم أنيق.', 620, 'women'],
            ['بدلة رياضية نسائية', 'بدلة رياضية كاملة للنساء.', 320, 'women'],
            ['حقيبة لابتوب محمية', 'حقيبة لابتوب بطبقة حماية.', 260, 'unisex'],
            ['طقم أقلام فاخر', 'طقم أقلام فاخر بتغليف هدايا.', 180, 'unisex'],
        ];

        $sizes = ['XS', 'S', 'M', 'L', 'XL', 'XXL', '36', '38', '40', '42', '44', 'One Size'];

        $addedProducts = 0;
        $addedVariants = 0;
        $addedImages = 0;
        $skipped = 0;

        foreach ($productsData as $data) {
            [$name, $description, $basePrice, $gender] = $data;

            // تخطي إذا كان المنتج موجوداً بالاسم
            if (Product::where('name', $name)->exists()) {
                $skipped++;
                continue;
            }

            // السعر مع الخصم
            $price = $basePrice + rand(-30, 50);
            $price = max(50, $price);
            $hasDiscount = rand(0, 1) === 1;
            $discountPrice = $hasDiscount ? round($price * (1 - rand(10, 40) / 100), 2) : null;

            // اختيار عشوائي للتصنيفات
            $category = $categories->random();
            $subCategory = $subCategories->isNotEmpty() && rand(0, 2) > 0 ? $subCategories->random() : null;
            $brand = $brands->isNotEmpty() && rand(0, 2) > 0 ? $brands->random() : null;
            $sizeGuide = !empty($sizeGuides) && rand(0, 1) === 1
                ? $sizeGuides[array_rand($sizeGuides)]
                : null;

            $productData = [
                'name'            => $name,
                'description'     => $description,
                'price'           => $price,
                'discount_price'  => $discountPrice,
                'is_active'       => true,
                'is_featured'     => rand(0, 3) === 0,
                'category_id'     => $category->id,
                'sub_category_id' => $subCategory?->id,
                'brand_id'        => $brand?->id,
            ];

            if ($hasSlug) $productData['slug'] = Str::slug($name) . '-' . uniqid();
            if ($hasSizeGuide) $productData['size_guide_id'] = $sizeGuide;

            $product = Product::create($productData);
            $addedProducts++;

            // متغيرات: 2-4 ألوان × 2-4 مقاسات
            $randomColors = collect($colors)->random(min(rand(2, 4), count($colors)))->toArray();
            $randomSizes = collect($sizes)->random(min(rand(2, 4), count($sizes)))->toArray();

            foreach ($randomColors as $color) {
                foreach ($randomSizes as $size) {
                    ProductVariant::create([
                        'product_id'     => $product->id,
                        'color'          => $color,
                        'size'           => $size,
                        'stock_quantity' => rand(0, 10) === 0 ? 0 : rand(5, 40),
                    ]);
                    $addedVariants++;
                }
            }

            // صور SVG (3-5 لكل منتج)
            $imageCount = rand(3, 5);
            for ($i = 0; $i < $imageCount; $i++) {
                ProductImage::create([
                    'product_id' => $product->id,
                    'image_path' => 'data:image/svg+xml;base64,' . base64_encode(
                        $this->generateSvg($name, $i + 1)
                    ),
                    'is_primary' => $i === 0,
                ]);
                $addedImages++;
            }
        }

        $this->command->line("   ✓ {$addedProducts} منتج جديد");
        $this->command->line("   ✓ {$addedVariants} متغير");
        $this->command->line("   ✓ {$addedImages} صورة");
        if ($skipped > 0) {
            $this->command->line("   ℹ {$skipped} منتج تم تخطيه (موجود مسبقاً)");
        }
        $this->command->line('   ℹ المجموع الآن: ' . Product::count() . ' منتج');
    }

    private function generateSvg(string $name, int $index): string
    {
        $short = mb_substr($name, 0, 25);
        $palettes = [
            ['bg' => '#E8F0F8', 'shape' => '#005a96', 'accent' => '#14b8a6'],
            ['bg' => '#FEF3C7', 'shape' => '#D97706', 'accent' => '#F59E0B'],
            ['bg' => '#DBEAFE', 'shape' => '#3B82F6', 'accent' => '#60A5FA'],
            ['bg' => '#FCE7F3', 'shape' => '#EC4899', 'accent' => '#F472B6'],
            ['bg' => '#D1FAE5', 'shape' => '#10B981', 'accent' => '#34D399'],
            ['bg' => '#E0E7FF', 'shape' => '#6366F1', 'accent' => '#818CF8'],
            ['bg' => '#FEE2E2', 'shape' => '#DC2626', 'accent' => '#F87171'],
            ['bg' => '#F3E8FF', 'shape' => '#A855F7', 'accent' => '#C084FC'],
        ];
        $c = $palettes[($index - 1) % count($palettes)];

        return <<<SVG
<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 600 800" fill="none">
    <rect width="600" height="800" fill="{$c['bg']}"/>
    <rect x="60" y="100" width="480" height="600" rx="20" fill="white" opacity="0.5"/>
    <circle cx="300" cy="240" r="70" fill="{$c['shape']}" opacity="0.9"/>
    <rect x="180" y="340" width="240" height="180" rx="12" fill="{$c['accent']}" opacity="0.7"/>
    <text x="300" y="620" font-family="Cairo, Arial, sans-serif" font-size="24" font-weight="bold" fill="#0f0f0f" text-anchor="middle">{$short}</text>
    <text x="300" y="660" font-family="Cairo, Arial, sans-serif" font-size="16" fill="#6b6b5e" text-anchor="middle">صورة {$index}</text>
</svg>
SVG;
    }
}