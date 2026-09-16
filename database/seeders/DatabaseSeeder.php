<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // إنشاء مستخدم اختباري (فقط في بيئة التطوير)
        // User::factory(10)->create();

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        // تشغيل كل الـ Seeders
        $this->call([
            PageSeeder::class,
            FaqSeeder::class,
            ColorSeeder::class,
            SizeGuideSeeder::class,
        ]);
    }
}

$this->call([
    PageSeeder::class,
    FaqSeeder::class,
    ColorSeeder::class,
    SizeGuideSeeder::class,
    SiteSettingSeeder::class,  // ← أضف هذا
]);