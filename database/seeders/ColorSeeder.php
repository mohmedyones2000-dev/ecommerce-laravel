<?php

namespace Database\Seeders;

use App\Models\Color;
use Illuminate\Database\Seeder;

class ColorSeeder extends Seeder
{
    public function run(): void
    {
        $colors = [
            ['name' => 'أحمر',       'hex_code' => '#ef4444'],
            ['name' => 'أزرق',       'hex_code' => '#3b82f6'],
            ['name' => 'أخضر',       'hex_code' => '#22c55e'],
            ['name' => 'أصفر',       'hex_code' => '#eab308'],
            ['name' => 'أسود',       'hex_code' => '#000000'],
            ['name' => 'أبيض',       'hex_code' => '#ffffff'],
            ['name' => 'رمادي',      'hex_code' => '#6b7280'],
            ['name' => 'بني',        'hex_code' => '#78350f'],
            ['name' => 'بيج',        'hex_code' => '#f5f5dc'],
            ['name' => 'وردي',       'hex_code' => '#ec4899'],
            ['name' => 'بنفسجي',     'hex_code' => '#a855f7'],
            ['name' => 'برتقالي',    'hex_code' => '#f97316'],
            ['name' => 'تركوازي',    'hex_code' => '#14b8a6'],
            ['name' => 'كحلي',       'hex_code' => '#1e3a8a'],
            ['name' => 'ذهبي',       'hex_code' => '#d4af37'],
            ['name' => 'فضي',        'hex_code' => '#c0c0c0'],
        ];

        foreach ($colors as $color) {
            Color::updateOrCreate(['name' => $color['name']], $color);
        }
    }
}