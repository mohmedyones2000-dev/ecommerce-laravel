<?php

namespace Database\Seeders;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\SizeGuide;
use App\Models\SubCategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        // ✅ بيانات المنتجات (مقسمة حسب الجنس)
        $products = [
            // ==================== رجالي ====================
            [
                'name'        => 'قميص قطني كلاسيك',
                'description' => 'قميص قطني 100% بتصميم كلاسيكي أنيق، مناسب للمناسبات الرسمية والعمل اليومي.',
                'price'       => 150,
                'gender'      => 'men',
                'category'    => 'men-clothing',
                'sub_category'=> 'men-shirts',
                'brand'       => 'Zara',
                'is_featured' => true,
                'size_guide'  => 'مقاسات الرجالي',
            ],
            [
                'name'        => 'تيشيرت قطن أساسي',
                'description' => 'تيشيرت قطني مريح بألوان متعددة، مثالي للاستخدام اليومي.',
                'price'       => 80,
                'gender'      => 'men',
                'category'    => 'men-clothing',
                'sub_category'=> 'men-tshirts',
                'brand'       => 'Nike',
                'is_featured' => false,
                'size_guide'  => 'مقاسات الرجالي',
            ],
            [
                'name'        => 'بنطال جينز مستقيم',
                'description' => 'بنطال جينز بقصة مستقيمة مريحة، مصنوع من قماش عالي الجودة.',
                'price'       => 220,
                'gender'      => 'men',
                'category'    => 'men-clothing',
                'sub_category'=> 'men-pants',
                'brand'       => 'Levi\'s',
                'is_featured' => true,
                'size_guide'  => 'مقاسات الرجالي',
            ],
            [
                'name'        => 'جاكيت جلد أسود',
                'description' => 'جاكيت جلد أنيق بتصميم عصري، مناسب لفصلي الخريف والشتاء.',
                'price'       => 550,
                'gender'      => 'men',
                'category'    => 'men-clothing',
                'sub_category'=> 'men-jackets',
                'brand'       => 'Gucci',
                'is_featured' => true,
                'size_guide'  => 'مقاسات الرجالي',
            ],
            [
                'name'        => 'بدلة رسمية كاملة',
                'description' => 'بدلة رسمية أنيقة من قماش فاخر، مناسبة للأعراس والمناسبات الرسمية.',
                'price'       => 850,
                'gender'      => 'men',
                'category'    => 'men-clothing',
                'sub_category'=> 'men-suits',
                'brand'       => 'Tommy Hilfiger',
                'is_featured' => true,
                'size_guide'  => 'مقاسات الرجالي',
            ],

            // ==================== نسائي ====================
            [
                'name'        => 'فستان سهرة طويل',
                'description' => 'فستان سهرة طويل بتصميم راقٍ، مثالي للمناسبات والحفلات.',
                'price'       => 650,
                'gender'      => 'women',
                'category'    => 'women-clothing',
                'sub_category'=> 'women-dresses',
                'brand'       => 'Zara',
                'is_featured' => true,
                'size_guide'  => 'مقاسات النسائي',
            ],
            [
                'name'        => 'بلوزة حرير أنيقة',
                'description' => 'بلوزة حرير ناعمة بتصميم أنيق، مناسبة للعمل والمناسبات.',
                'price'       => 180,
                'gender'      => 'women',
                'category'    => 'women-clothing',
                'sub_category'=> 'women-blouses',
                'brand'       => 'Mango',
                'is_featured' => false,
                'size_guide'  => 'مقاسات النسائي',
            ],
            [
                'name'        => 'تنورة ميدي بليسيه',
                'description' => 'تنورة ميدي بطيات بليسيه أنيقة، تصميم عصري ومريح.',
                'price'       => 200,
                'gender'      => 'women',
                'category'    => 'women-clothing',
                'sub_category'=> 'women-skirts',
                'brand'       => 'H&M',
                'is_featured' => false,
                'size_guide'  => 'مقاسات النسائي',
            ],
            [
                'name'        => 'عباية مطرزة',
                'description' => 'عباية سوداء أنيقة بتطريز يدوي على الأكمام، قماش فاخر ومريح.',
                'price'       => 400,
                'gender'      => 'women',
                'category'    => 'women-clothing',
                'sub_category'=> 'women-abayas',
                'brand'       => 'Mango',
                'is_featured' => true,
                'size_guide'  => 'مقاسات النسائي',
            ],
            [
                'name'        => 'جاكيت شتوي نسائي',
                'description' => 'جاكيت شتوي دافئ بقبة، مناسب للأجواء الباردة.',
                'price'       => 450,
                'gender'      => 'women',
                'category'    => 'women-clothing',
                'sub_category'=> 'women-jackets',
                'brand'       => 'H&M',
                'is_featured' => false,
                'size_guide'  => 'مقاسات النسائي',
            ],

            // ==================== أطفال ====================
            [
                'name'        => 'طقم أولادي قطني',
                'description' => 'طقم قطني مريح للأولاد، مكون من تيشيرت وبنطال.',
                'price'       => 120,
                'gender'      => 'kids',
                'category'    => 'kids-clothing',
                'sub_category'=> 'kids-boys',
                'brand'       => 'Nike',
                'is_featured' => false,
                'size_guide'  => 'مقاسات الأطفال',
            ],
            [
                'name'        => 'فستان بناتي مزهّر',
                'description' => 'فستان بناتي أنيق بطبعة زهور، قماش ناعم ومريح.',
                'price'       => 150,
                'gender'      => 'kids',
                'category'    => 'kids-clothing',
                'sub_category'=> 'kids-girls',
                'brand'       => 'H&M',
                'is_featured' => true,
                'size_guide'  => 'مقاسات الأطفال',
            ],
            [
                'name'        => 'طقم حديثي الولادة',
                'description' => 'طقم قطني ناعم لحديثي الولادة، مكون من 3 قطع.',
                'price'       => 100,
                'gender'      => 'kids',
                'category'    => 'kids-clothing',
                'sub_category'=> 'kids-newborn',
                'brand'       => 'Mango',
                'is_featured' => false,
                'size_guide'  => 'مقاسات الأطفال',
            ],

            // ==================== رياضي ====================
            [
                'name'        => 'طقم رياضي كامل',
                'description' => 'طقم رياضي من قطعتين، قماش رياضي يسمح بالتهوية.',
                'price'       => 280,
                'gender'      => 'unisex',
                'category'    => 'sportswear',
                'sub_category'=> 'sport-sets',
                'brand'       => 'Adidas',
                'is_featured' => true,
                'size_guide'  => 'مقاسات الرجالي',
            ],
            [
                'name'        => 'حذاء رياضي احترافي',
                'description' => 'حذاء رياضي مريح بتصميم عصري، مناسب للجري والتمارين.',
                'price'       => 350,
                'gender'      => 'unisex',
                'category'    => 'sportswear',
                'sub_category'=> 'sport-shoes',
                'brand'       => 'Puma',
                'is_featured' => true,
                'size_guide'  => null,
            ],

            // ==================== إكسسوارات ====================
            [
                'name'        => 'حقيبة يد جلدية',
                'description' => 'حقيبة يد أنيقة من الجلد الطبيعي، تصميم عصري وعملي.',
                'price'       => 320,
                'gender'      => 'women',
                'category'    => 'accessories',
                'sub_category'=> 'accessory-bags',
                'brand'       => 'Gucci',
                'is_featured' => true,
                'size_guide'  => null,
            ],
            [
                'name'        => 'حزام جلد كلاسيك',
                'description' => 'حزام جلد كلاسيكي بإبزيم معدني، يناسب جميع الملابس.',
                'price'       => 120,
                'gender'      => 'men',
                'category'    => 'accessories',
                'sub_category'=> 'accessory-belts',
                'brand'       => 'Levi\'s',
                'is_featured' => false,
                'size_guide'  => null,
            ],
            [
                'name'        => 'قبعة شمس رياضية',
                'description' => 'قبعة رياضية بحماية من الشمس، قابلة للتعديل.',
                'price'       => 80,
                'gender'      => 'unisex',
                'category'    => 'accessories',
                'sub_category'=> 'accessory-hats',
                'brand'       => 'Nike',
                'is_featured' => false,
                'size_guide'  => null,
            ],
            [
                'name'        => 'ساعة يد أنيقة',
                'description' => 'ساعة يد بتصميم أنيق، مقاومة للماء.',
                'price'       => 500,
                'gender'      => 'men',
                'category'    => 'accessories',
                'sub_category'=> 'accessory-watches',
                'brand'       => 'Calvin Klein',
                'is_featured' => true,
                'size_guide'  => null,
            ],
        ];

        foreach ($products as $data) {
            $category = Category::where('slug', $data['category'])->first();
            $subCategory = SubCategory::where('slug', $data['sub_category'])->first();
            $brand = Brand::where('name', $data['brand'])->first();
            $sizeGuide = $data['size_guide'] ? SizeGuide::where('name', $data['size_guide'])->first() : null;

            if (!$category) {
                continue;
            }

            // ✅ إنشاء منتج مع خصم عشوائي لبعض المنتجات
            $hasDiscount = rand(0, 1) === 1;
            $discountPrice = $hasDiscount ? round($data['price'] * 0.85, 2) : null;

            Product::updateOrCreate(
                ['slug' => Str::slug($data['name']) . '-' . uniqid()],
                [
                    'name'           => $data['name'],
                    'slug'           => Str::slug($data['name']) . '-' . uniqid(),
                    'description'    => $data['description'],
                    'price'          => $data['price'],
                    'discount_price' => $discountPrice,
                    'is_active'      => true,
                    'is_featured'    => $data['is_featured'],
                    'category_id'    => $category->id,
                    'sub_category_id'=> $subCategory?->id,
                    'brand_id'       => $brand?->id,
                    'size_guide_id'  => $sizeGuide?->id,
                ]
            );
        }

        $this->command->info('✅ Products seeded: ' . Product::count() . ' products');
    }
}