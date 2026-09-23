<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\SubCategory;
use Illuminate\Database\Seeder;

class SubCategorySeeder extends Seeder
{
    public function run(): void
    {
        // ✅ ربط كل تصنيف فرعي بتصنيف رئيسي
        $data = [
            // رجالي
            'men-clothing' => [
                ['name' => 'قمصان',    'slug' => 'men-shirts'],
                ['name' => 'بناطيل',   'slug' => 'men-pants'],
                ['name' => 'تيشيرتات', 'slug' => 'men-tshirts'],
                ['name' => 'جاكيتات',  'slug' => 'men-jackets'],
                ['name' => 'بدلات',    'slug' => 'men-suits'],
            ],
            // نسائي
            'women-clothing' => [
                ['name' => 'فساتين',   'slug' => 'women-dresses'],
                ['name' => 'بلوزات',   'slug' => 'women-blouses'],
                ['name' => 'تنانير',   'slug' => 'women-skirts'],
                ['name' => 'عبايات',   'slug' => 'women-abayas'],
                ['name' => 'جاكيتات نسائية', 'slug' => 'women-jackets'],
            ],
            // أطفال
            'kids-clothing' => [
                ['name' => 'أولادي',       'slug' => 'kids-boys'],
                ['name' => 'بناتي',        'slug' => 'kids-girls'],
                ['name' => 'حديثي الولادة', 'slug' => 'kids-newborn'],
            ],
            // رياضي
            'sportswear' => [
                ['name' => 'أطقم رياضية',   'slug' => 'sport-sets'],
                ['name' => 'أحذية رياضية',  'slug' => 'sport-shoes'],
                ['name' => 'إكسسوارات رياضية', 'slug' => 'sport-accessories'],
            ],
            // إكسسوارات
            'accessories' => [
                ['name' => 'حقائب',  'slug' => 'accessory-bags'],
                ['name' => 'أحزمة',  'slug' => 'accessory-belts'],
                ['name' => 'قبعات',  'slug' => 'accessory-hats'],
                ['name' => 'ساعات',  'slug' => 'accessory-watches'],
            ],
        ];

        foreach ($data as $categorySlug => $subCategories) {
            $category = Category::where('slug', $categorySlug)->first();

            if (!$category) {
                continue;
            }

            foreach ($subCategories as $subCategory) {
                $sub = SubCategory::updateOrCreate(
                    ['slug' => $subCategory['slug']],
                    $subCategory
                );

                // ✅ ربط التصنيف الفرعي بالتصنيف الرئيسي (many-to-many)
                $sub->categories()->syncWithoutDetaching([$category->id]);
            }
        }

        $this->command->info('✅ SubCategories seeded: ' . SubCategory::count() . ' subcategories');
    }
}