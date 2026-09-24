<?php

namespace Database\Seeders;

use App\Models\Color;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class ColorSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('→ إضافة ألوان جديدة...');

        // 30 لون جديد (لا تكرر الموجودة في ColorSeeder)
        $colors = [
            ['name' => 'أزرق ملكي',      'hex_code' => '#4169E1'],
            ['name' => 'أزرق سماوي',      'hex_code' => '#0EA5E9'],
            ['name' => 'أزرق فاتح',       'hex_code' => '#60A5FA'],
            ['name' => 'أخضر زمردي',      'hex_code' => '#059669'],
            ['name' => 'أخضر فاتح',       'hex_code' => '#4ADE80'],
            ['name' => 'أخضر داكن',       'hex_code' => '#065F46'],
            ['name' => 'أحمر داكن',       'hex_code' => '#991B1B'],
            ['name' => 'أحمر قرمزي',      'hex_code' => '#DC2626'],
            ['name' => 'عنابي',           'hex_code' => '#7F1D1D'],
            ['name' => 'توتي',            'hex_code' => '#E11D48'],
            ['name' => 'زهري',            'hex_code' => '#FB7185'],
            ['name' => 'وردي فاتح',       'hex_code' => '#FBCFE8'],
            ['name' => 'بنفسجي داكن',     'hex_code' => '#6D28D9'],
            ['name' => 'أرجواني',         'hex_code' => '#8B5CF6'],
            ['name' => 'أرجواني فاتح',    'hex_code' => '#C084FC'],
            ['name' => 'ليموني',          'hex_code' => '#84CC16'],
            ['name' => 'ليموني فاتح',     'hex_code' => '#D9F99D'],
            ['name' => 'خردلي',           'hex_code' => '#CA8A04'],
            ['name' => 'عنبري',           'hex_code' => '#F59E0B'],
            ['name' => 'برتقالي داكن',    'hex_code' => '#EA580C'],
            ['name' => 'مرجاني',          'hex_code' => '#FB923C'],
            ['name' => 'بيج غامق',        'hex_code' => '#D6C9A8'],
            ['name' => 'كريمي',           'hex_code' => '#FEF3C7'],
            ['name' => 'بني فاتح',        'hex_code' => '#A16207'],
            ['name' => 'بني غامق',        'hex_code' => '#451A03'],
            ['name' => 'شوكولاتي',        'hex_code' => '#3F2212'],
            ['name' => 'تركوازي فاتح',    'hex_code' => '#5EEAD4'],
            ['name' => 'نحاسي',           'hex_code' => '#B87333'],
            ['name' => 'بلاتيني',         'hex_code' => '#E5E4E2'],
            ['name' => 'رمادي داكن',      'hex_code' => '#374151'],
        ];

        $hasHex = Schema::hasColumn('colors', 'hex_code');
        $hasActive = Schema::hasColumn('colors', 'is_active');

        $added = 0;
        foreach ($colors as $color) {
            if (Color::where('name', $color['name'])->exists()) {
                continue;
            }

            $data = ['name' => $color['name']];
            if ($hasHex) $data['hex_code'] = $color['hex_code'];
            if ($hasActive) $data['is_active'] = true;

            Color::create($data);
            $added++;
        }

        $this->command->line("   ✓ {$added} لون جديد");
        $this->command->line('   ℹ المجموع الآن: ' . Color::count());
    }
}