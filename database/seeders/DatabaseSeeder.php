<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('بدء زراعة البيانات...');
        $this->command->newLine();

        $this->call([
            // المجموعة الأساسية
            UserSeeder::class,
            SiteSettingSeeder::class,
            CitySeeder::class,
            CategorySeeder::class,
            SubCategorySeeder::class,
            BrandSeeder::class,
            ColorSeeder::class,
            SizeGuideSeeder::class,
            SizeGuideItemSeeder::class,

            // المنتجات
            AddPlaceholderImagesSeeder::class,
            ProductSeeder::class,
            ProductVariantSeeder::class,
            ProductImageSeeder::class,

            // محتوى
            PageSeeder::class,
            FaqSeeder::class,
            CouponSeeder::class,

            // مستخدمين
            AddUsersSeeder::class,
            AddNewsletterSubscribersSeeder::class,

            // طلبات وتقييمات
            AddressSeeder::class,
            OrderSeeder::class,
            ReviewSeeder::class,

            // إشعارات
            NotificationSeeder::class,
        ]);

        $this->command->newLine();
        $this->command->info('اكتملت الزراعة بنجاح!');
    }
}