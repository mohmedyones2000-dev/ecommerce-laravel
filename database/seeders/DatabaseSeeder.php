<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('🌱 بدء زراعة البيانات...');
        $this->command->newLine();

        $this->call([
            // المجموعة 1: المستخدمين والإعدادات
            UserSeeder::class,
            SiteSettingSeeder::class,

            // المجموعة 2: البيانات المرجعية
            CitySeeder::class,
            CategorySeeder::class,
            SubCategorySeeder::class,
            BrandSeeder::class,
            ColorSeeder::class,

            // ✅ المجموعة 3: أدلة المقاسات
    SizeGuideSeeder::class,
    SizeGuideItemSeeder::class,


    ProductSeeder::class,
    ProductVariantSeeder::class,
    ProductImageSeeder::class,
        ]);

        $this->command->newLine();
        $this->command->info('🎉 اكتملت الزراعة بنجاح!');
        $this->command->newLine();

        $this->command->table(
            ['البريد الإلكتروني', 'كلمة المرور', 'الدور'],
            [
                ['admin@matjari.com',          'password', '🛡️ مشرف'],
                ['ahmed.manager@matjari.com',  'password', '👔 مدير (منتجات)'],
                ['sara.manager@matjari.com',   'password', '👔 مديرة (طلبات)'],
                ['khaled.manager@matjari.com', 'password', '👔 مدير (مختلط)'],
                ['mohamed@test.com',           'password', '🛒 زبون'],
                ['fatima@test.com',            'password', '🛒 زبون'],
            ]
        );
    }
}