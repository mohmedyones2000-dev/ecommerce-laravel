<?php

namespace Database\Seeders;

use App\Models\City;
use Illuminate\Database\Seeder;

class CitySeeder extends Seeder
{
    public function run(): void
    {
        $cities = [
            ['name' => 'غزة',           'code' => 'GZA', 'shipping_cost' => 15, 'is_free_shipping' => false],
            ['name' => 'خان يونس',      'code' => 'KHU', 'shipping_cost' => 20, 'is_free_shipping' => false],
            ['name' => 'رفح',           'code' => 'RFH', 'shipping_cost' => 25, 'is_free_shipping' => false],
            ['name' => 'دير البلح',     'code' => 'DBL', 'shipping_cost' => 18, 'is_free_shipping' => false],
            ['name' => 'جباليا',        'code' => 'JBL', 'shipping_cost' => 15, 'is_free_shipping' => false],
            ['name' => 'بيت لاهيا',     'code' => 'BLH', 'shipping_cost' => 15, 'is_free_shipping' => false],
            ['name' => 'بيت حانون',     'code' => 'BHN', 'shipping_cost' => 15, 'is_free_shipping' => false],
            ['name' => 'النصيرات',      'code' => 'NSR', 'shipping_cost' => 18, 'is_free_shipping' => false],
            ['name' => 'البريج',        'code' => 'BRJ', 'shipping_cost' => 20, 'is_free_shipping' => false],
            ['name' => 'المغازي',       'code' => 'MGZ', 'shipping_cost' => 18, 'is_free_shipping' => false],
            ['name' => 'بني سهيلا',     'code' => 'BSL', 'shipping_cost' => 22, 'is_free_shipping' => false],
            ['name' => 'عبسان',         'code' => 'ABS', 'shipping_cost' => 22, 'is_free_shipping' => false],
        ];

        foreach ($cities as $city) {
            City::updateOrCreate(
                ['name' => $city['name']],
                $city
            );
        }

        $this->command->info('✅ Cities seeded: ' . City::count() . ' cities');
    }
}