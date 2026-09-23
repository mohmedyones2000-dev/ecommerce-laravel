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
            // ============ المجموعة 1 ============
            UserSeeder::class,
            SiteSettingSeeder::class,

            // ============ المجموعة 2 ============
            CitySeeder::class,
            CategorySeeder::class,
            SubCategorySeeder::class,
            BrandSeeder::class,
            ColorSeeder::class,

            // ============ المجموعة 3 ============
            SizeGuideSeeder::class,
            SizeGuideItemSeeder::class,

            // ============ المجموعة 4 ============
            ProductSeeder::class,
            ProductVariantSeeder::class,
            ProductImageSeeder::class,

            // ============ المجموعة 5 ============
            PageSeeder::class,
            FaqSeeder::class,
            CouponSeeder::class,
            AddressSeeder::class,
            OrderSeeder::class,
            ReviewSeeder::class,
            NotificationSeeder::class,
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