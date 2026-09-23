<?php

namespace Database\Seeders;

use App\Models\Brand;
use Illuminate\Database\Seeder;

class BrandSeeder extends Seeder
{
    public function run(): void
    {
        $brands = [
            ['name' => 'Nike',       'logo' => null],
            ['name' => 'Adidas',     'logo' => null],
            ['name' => 'Zara',       'logo' => null],
            ['name' => 'H&M',        'logo' => null],
            ['name' => 'Levi\'s',    'logo' => null],
            ['name' => 'Puma',       'logo' => null],
            ['name' => 'Gucci',      'logo' => null],
            ['name' => 'Mango',      'logo' => null],
            ['name' => 'Tommy Hilfiger', 'logo' => null],
            ['name' => 'Calvin Klein',   'logo' => null],
        ];

        foreach ($brands as $brand) {
            Brand::updateOrCreate(
                ['name' => $brand['name']],
                $brand
            );
        }

        $this->command->info('✅ Brands seeded: ' . Brand::count() . ' brands');
    }
}