<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\SubCategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class SubCategorySeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('→ إضافة 20 تصنيف فرعي جديد...');

        // 20 تصنيف فرعي إضافي (لا تكرر SubCategorySeeder)
        $data = [
            // رجالي (4 إضافية)
            'men-clothing' => [
                ['name' => 'أوشحة',       'slug' => 'men-scarves'],
                ['name' => 'معاطف',       'slug' => 'men-coats'],
                ['name' => 'شورتات',      'slug' => 'men-shorts'],
                ['name' => 'ملابس داخلية', 'slug' => 'men-underwear'],
            ],
            // نسائي (4 إضافية)
            'women-clothing' => [
                ['name' => 'كنزات',       'slug' => 'women-sweaters'],
                ['name' => 'أطقم نسائية',  'slug' => 'women-sets'],
                ['name' => 'جوارب',       'slug' => 'women-socks'],
                ['name' => 'ملابس نوم',   'slug' => 'women-sleepwear'],
            ],
            // أطفال (3 إضافية)
            'kids-clothing' => [
                ['name' => 'طقم ولادي',   'slug' => 'kids-sets'],
                ['name' => 'جاكيتات أطفال', 'slug' => 'kids-jackets'],
                ['name' => 'أحذية أطفال',  'slug' => 'kids-shoes'],
            ],
            // رياضي (3 إضافية)
            'sportswear' => [
                ['name' => 'بناطيل رياضية', 'slug' => 'sport-pants'],
                ['name' => 'تيشيرتات رياضية', 'slug' => 'sport-tshirts'],
                ['name' => 'جاكيتات رياضية', 'slug' => 'sport-jackets'],
            ],
            // إكسسوارات (6 إضافية)
            'accessories' => [
                ['name' => 'نظارات',      'slug' => 'accessory-glasses'],
                ['name' => 'محافظ',       'slug' => 'accessory-wallets'],
                ['name' => 'حقائب ظهر',   'slug' => 'accessory-backpacks'],
                ['name' => 'مجوهرات',     'slug' => 'accessory-jewelry'],
                ['name' => 'عطور',        'slug' => 'accessory-perfumes'],
                ['name' => 'أقلام فاخرة', 'slug' => 'accessory-pens'],
            ],
        ];

        $hasSlug = Schema::hasColumn('sub_categories', 'slug');
        $hasPivot = Schema::hasTable('category_sub_category');

        $added = 0;
        $skipped = 0;

        foreach ($data as $categorySlug => $subCategories) {
            $category = Category::where('slug', $categorySlug)->first();

            if (!$category) {
                $this->command->warn("   ⚠ تصنيف رئيسي غير موجود: {$categorySlug}");
                continue;
            }

            foreach ($subCategories as $subData) {
                // فحص التكرار بالاسم
                if (SubCategory::where('name', $subData['name'])->exists()) {
                    $skipped++;
                    continue;
                }

                $createData = ['name' => $subData['name']];
                if ($hasSlug) {
                    $createData['slug'] = $subData['slug'];
                }

                $sub = SubCategory::create($createData);
                $added++;

                // ربط بالتصنيف الرئيسي
                if ($hasPivot) {
                    $sub->categories()->syncWithoutDetaching([$category->id]);
                }
            }
        }

        $this->command->line("   ✓ {$added} تصنيف فرعي جديد");
        if ($skipped > 0) {
            $this->command->line("   ℹ {$skipped} تم تخطيه (موجود مسبقاً)");
        }
        $this->command->line('   ℹ المجموع الآن: ' . SubCategory::count() . ' تصنيف فرعي');
    }
}