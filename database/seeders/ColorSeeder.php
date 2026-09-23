<?php

namespace Database\Seeders;

use App\Models\Color;
use Illuminate\Database\Seeder;

class ColorSeeder extends Seeder
{
    public function run(): void
    {
        $colors = [
            ['name' => 'أبيض',    'hex_code' => '#FFFFFF', 'is_active' => true],
            ['name' => 'أسود',    'hex_code' => '#000000', 'is_active' => true],
            ['name' => 'رمادي',   'hex_code' => '#6B7280', 'is_active' => true],
            ['name' => 'أحمر',    'hex_code' => '#EF4444', 'is_active' => true],
            ['name' => 'أزرق',    'hex_code' => '#3B82F6', 'is_active' => true],
            ['name' => 'كحلي',    'hex_code' => '#1E3A8A', 'is_active' => true],
            ['name' => 'أخضر',    'hex_code' => '#22C55E', 'is_active' => true],
            ['name' => 'أصفر',    'hex_code' => '#EAB308', 'is_active' => true],
            ['name' => 'برتقالي', 'hex_code' => '#F97316', 'is_active' => true],
            ['name' => 'بني',     'hex_code' => '#78350F', 'is_active' => true],
            ['name' => 'بيج',     'hex_code' => '#F5F5DC', 'is_active' => true],
            ['name' => 'وردي',    'hex_code' => '#EC4899', 'is_active' => true],
            ['name' => 'بنفسجي',  'hex_code' => '#A855F7', 'is_active' => true],
            ['name' => 'تركوازي', 'hex_code' => '#14B8A6', 'is_active' => true],
            ['name' => 'ذهبي',    'hex_code' => '#D4AF37', 'is_active' => true],
            ['name' => 'فضي',     'hex_code' => '#C0C0C0', 'is_active' => true],
        ];

        foreach ($colors as $color) {
            Color::updateOrCreate(
                ['name' => $color['name']],
                $color
            );
        }

        $this->command->info('✅ Colors seeded: ' . Color::count() . ' colors');
    }
}