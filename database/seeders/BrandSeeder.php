<?php

namespace Database\Seeders;

use App\Models\Brand;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class BrandSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('→ إضافة علامات تجارية جديدة...');

        // 15 علامة جديدة (لا تكرر الموجود في BrandSeeder)
        $brands = [
            'Zara',
            'Nike',
            'Adidas',
            'H&M',
            'Mango',
            'Bershka',
            'Gucci',
            'Prada',
            'Chanel',
            'Dior',
            'Stradivarius',
            'Massimo Dutti',
            'Levi\'s',
            'Oysho',
            'Uterque',
        ];

        $hasSlug = Schema::hasColumn('brands', 'slug');
        $hasLogo = Schema::hasColumn('brands', 'logo');

        $added = 0;
        foreach ($brands as $name) {
            // skip if already exists (safety)
            if (Brand::where('name', $name)->exists()) {
                continue;
            }

            $data = ['name' => $name];
            if ($hasSlug) $data['slug'] = Str::slug($name);
            if ($hasLogo) $data['logo'] = null;

            Brand::create($data);
            $added++;
        }

        $this->command->line("   ✓ {$added} علامة تجارية جديدة");
        $this->command->line('   ℹ المجموع الآن: ' . Brand::count());
    }
}