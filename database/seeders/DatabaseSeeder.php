<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            PageSeeder::class,
            FaqSeeder::class,
            ColorSeeder::class,
            SizeGuideSeeder::class,
            // ... باقي الـ Seeders
        ]);
    }
}