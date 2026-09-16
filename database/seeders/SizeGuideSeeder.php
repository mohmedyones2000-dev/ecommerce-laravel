<?php

namespace Database\Seeders;

use App\Models\SizeGuide;
use App\Models\SizeGuideItem;
use Illuminate\Database\Seeder;

class SizeGuideSeeder extends Seeder
{
    public function run(): void
    {
        // دليل مقاسات الرجالي
        $menGuide = SizeGuide::create([
            'name' => 'مقاسات الرجالي',
            'description' => 'القياسات بالسنتيمتر. إذا كان مقاسك بين مقاسين، اختر المقاس الأكبر.',
            'is_active' => true,
        ]);

        $menSizes = [
            ['size' => 'S',  'chest' => '86-91',  'waist' => '71-76',  'hips' => '86-91',  'length' => '68'],
            ['size' => 'M',  'chest' => '91-97',  'waist' => '76-81',  'hips' => '91-97',  'length' => '70'],
            ['size' => 'L',  'chest' => '97-102', 'waist' => '81-86',  'hips' => '97-102', 'length' => '72'],
            ['size' => 'XL', 'chest' => '102-107','waist' => '86-91',  'hips' => '102-107','length' => '74'],
            ['size' => 'XXL','chest' => '107-112','waist' => '91-97',  'hips' => '107-112','length' => '76'],
        ];

        foreach ($menSizes as $index => $size) {
            SizeGuideItem::create(array_merge($size, [
                'size_guide_id' => $menGuide->id,
                'sort_order' => $index,
            ]));
        }

        // دليل مقاسات النسائي
        $womenGuide = SizeGuide::create([
            'name' => 'مقاسات النسائي',
            'description' => 'القياسات بالسنتيمتر. القياسات تقريبية وقد تختلف قليلاً بين الموديلات.',
            'is_active' => true,
        ]);

        $womenSizes = [
            ['size' => 'XS', 'chest' => '76-81',  'waist' => '58-63',  'hips' => '84-89',  'length' => '60'],
            ['size' => 'S',  'chest' => '81-86',  'waist' => '63-68',  'hips' => '89-94',  'length' => '62'],
            ['size' => 'M',  'chest' => '86-91',  'waist' => '68-73',  'hips' => '94-99',  'length' => '64'],
            ['size' => 'L',  'chest' => '91-97',  'waist' => '73-79',  'hips' => '99-104', 'length' => '66'],
            ['size' => 'XL', 'chest' => '97-102', 'waist' => '79-86',  'hips' => '104-110','length' => '68'],
        ];

        foreach ($womenSizes as $index => $size) {
            SizeGuideItem::create(array_merge($size, [
                'size_guide_id' => $womenGuide->id,
                'sort_order' => $index,
            ]));
        }

        // دليل مقاسات الأطفال
        $kidsGuide = SizeGuide::create([
            'name' => 'مقاسات الأطفال',
            'description' => 'القياسات حسب العمر والسنتيمتر.',
            'is_active' => true,
        ]);

        $kidsSizes = [
            ['size' => '2-3 سنوات', 'chest' => '53-55', 'waist' => '50-52', 'hips' => '55-57', 'length' => '38'],
            ['size' => '4-5 سنوات', 'chest' => '56-58', 'waist' => '53-55', 'hips' => '58-60', 'length' => '42'],
            ['size' => '6-7 سنوات', 'chest' => '59-62', 'waist' => '56-58', 'hips' => '61-64', 'length' => '46'],
            ['size' => '8-9 سنوات', 'chest' => '63-66', 'waist' => '59-62', 'hips' => '65-68', 'length' => '50'],
        ];

        foreach ($kidsSizes as $index => $size) {
            SizeGuideItem::create(array_merge($size, [
                'size_guide_id' => $kidsGuide->id,
                'sort_order' => $index,
            ]));
        }
    }
}