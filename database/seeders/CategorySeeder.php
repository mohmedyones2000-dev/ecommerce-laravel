<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'ملابس رجالية', 'slug' => 'men-clothing'],
            ['name' => 'ملابس نسائية', 'slug' => 'women-clothing'],
            ['name' => 'ملابس أطفال',  'slug' => 'kids-clothing'],
            ['name' => 'ملابس رياضية', 'slug' => 'sportswear'],
            ['name' => 'إكسسوارات',    'slug' => 'accessories'],
        ];

        foreach ($categories as $category) {
            Category::updateOrCreate(
                ['slug' => $category['slug']],
                $category
            );
        }

        $this->command->info('✅ Categories seeded: ' . Category::count() . ' categories');
    }
}