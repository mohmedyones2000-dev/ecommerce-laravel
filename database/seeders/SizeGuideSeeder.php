<?php

namespace Database\Seeders;

use App\Models\SizeGuide;
use Illuminate\Database\Seeder;

class SizeGuideSeeder extends Seeder
{
    public function run(): void
    {
        $guides = [
            [
                'name'        => 'مقاسات الرجالي',
                'description' => 'القياسات بالسنتيمتر. إذا كان مقاسك بين مقاسين، اختر المقاس الأكبر.',
                'is_active'   => true,
            ],
            [
                'name'        => 'مقاسات النسائي',
                'description' => 'القياسات بالسنتيمتر. القياسات تقريبية وقد تختلف قليلاً بين الموديلات.',
                'is_active'   => true,
            ],
            [
                'name'        => 'مقاسات الأطفال',
                'description' => 'القياسات حسب العمر والسنتيمتر.',
                'is_active'   => true,
            ],
        ];

        foreach ($guides as $guide) {
            SizeGuide::updateOrCreate(
                ['name' => $guide['name']],
                $guide
            );
        }

        $this->command->info('✅ Size guides seeded: ' . SizeGuide::count() . ' guides');
    }
}