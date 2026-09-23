<?php

namespace Database\Seeders;

use App\Models\SizeGuide;
use App\Models\SizeGuideItem;
use Illuminate\Database\Seeder;

class SizeGuideItemSeeder extends Seeder
{
    public function run(): void
    {
        // ============================================
        // 1. مقاسات الرجالي
        // ============================================
        $menGuide = SizeGuide::where('name', 'مقاسات الرجالي')->first();

        if ($menGuide) {
            $menSizes = [
                ['size' => 'S',   'chest' => '86-91',   'waist' => '71-76',   'hips' => '86-91',   'length' => '68', 'sort_order' => 1],
                ['size' => 'M',   'chest' => '91-97',   'waist' => '76-81',   'hips' => '91-97',   'length' => '70', 'sort_order' => 2],
                ['size' => 'L',   'chest' => '97-102',  'waist' => '81-86',   'hips' => '97-102',  'length' => '72', 'sort_order' => 3],
                ['size' => 'XL',  'chest' => '102-107', 'waist' => '86-91',   'hips' => '102-107', 'length' => '74', 'sort_order' => 4],
                ['size' => 'XXL', 'chest' => '107-112', 'waist' => '91-97',   'hips' => '107-112', 'length' => '76', 'sort_order' => 5],
            ];

            foreach ($menSizes as $size) {
                SizeGuideItem::updateOrCreate(
                    ['size_guide_id' => $menGuide->id, 'size' => $size['size']],
                    array_merge($size, ['size_guide_id' => $menGuide->id])
                );
            }
        }

        // ============================================
        // 2. مقاسات النسائي
        // ============================================
        $womenGuide = SizeGuide::where('name', 'مقاسات النسائي')->first();

        if ($womenGuide) {
            $womenSizes = [
                ['size' => 'XS', 'chest' => '76-81',  'waist' => '58-63', 'hips' => '84-89',  'length' => '60', 'sort_order' => 1],
                ['size' => 'S',  'chest' => '81-86',  'waist' => '63-68', 'hips' => '89-94',  'length' => '62', 'sort_order' => 2],
                ['size' => 'M',  'chest' => '86-91',  'waist' => '68-73', 'hips' => '94-99',  'length' => '64', 'sort_order' => 3],
                ['size' => 'L',  'chest' => '91-97',  'waist' => '73-79', 'hips' => '99-104', 'length' => '66', 'sort_order' => 4],
                ['size' => 'XL', 'chest' => '97-102', 'waist' => '79-86', 'hips' => '104-110','length' => '68', 'sort_order' => 5],
            ];

            foreach ($womenSizes as $size) {
                SizeGuideItem::updateOrCreate(
                    ['size_guide_id' => $womenGuide->id, 'size' => $size['size']],
                    array_merge($size, ['size_guide_id' => $womenGuide->id])
                );
            }
        }

        // ============================================
        // 3. مقاسات الأطفال
        // ============================================
        $kidsGuide = SizeGuide::where('name', 'مقاسات الأطفال')->first();

        if ($kidsGuide) {
            $kidsSizes = [
                ['size' => '2-3 سنوات', 'chest' => '53-55', 'waist' => '50-52', 'hips' => '55-57', 'length' => '38', 'sort_order' => 1],
                ['size' => '4-5 سنوات', 'chest' => '56-58', 'waist' => '53-55', 'hips' => '58-60', 'length' => '42', 'sort_order' => 2],
                ['size' => '6-7 سنوات', 'chest' => '59-62', 'waist' => '56-58', 'hips' => '61-64', 'length' => '46', 'sort_order' => 3],
                ['size' => '8-9 سنوات', 'chest' => '63-66', 'waist' => '59-62', 'hips' => '65-68', 'length' => '50', 'sort_order' => 4],
                ['size' => '10-11 سنة', 'chest' => '67-70', 'waist' => '63-66', 'hips' => '69-72', 'length' => '54', 'sort_order' => 5],
            ];

            foreach ($kidsSizes as $size) {
                SizeGuideItem::updateOrCreate(
                    ['size_guide_id' => $kidsGuide->id, 'size' => $size['size']],
                    array_merge($size, ['size_guide_id' => $kidsGuide->id])
                );
            }
        }

        $this->command->info('✅ Size guide items seeded: ' . SizeGuideItem::count() . ' items');
    }
}