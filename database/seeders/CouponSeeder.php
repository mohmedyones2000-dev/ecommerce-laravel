<?php

namespace Database\Seeders;

use App\Models\Coupon;
use Illuminate\Database\Seeder;

class CouponSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('→ إضافة كوبونات جديدة...');

        // 10 كوبونات جديدة (لا تكرر الموجودة في CouponSeeder)
        $coupons = [
            [
                'code'             => 'SAVE20',
                'description'      => 'خصم 20% على جميع المنتجات',
                'type'             => 'percentage',
                'value'            => 20,
                'free_shipping'    => false,
                'min_order_amount' => 100,
                'usage_limit'      => 50,
                'used_count'       => 0,
                'expires_at'       => now()->addMonths(3),
                'is_active'        => true,
            ],
            [
                'code'             => 'WINTER25',
                'description'      => 'عرض الشتاء - خصم 25%',
                'type'             => 'percentage',
                'value'            => 25,
                'free_shipping'    => false,
                'min_order_amount' => 200,
                'usage_limit'      => 100,
                'used_count'       => 0,
                'expires_at'       => now()->addMonths(2),
                'is_active'        => true,
            ],
            [
                'code'             => 'FLAT100',
                'description'      => 'خصم ثابت 100 شيكل',
                'type'             => 'fixed',
                'value'            => 100,
                'free_shipping'    => false,
                'min_order_amount' => 500,
                'usage_limit'      => 10,
                'used_count'       => 0,
                'expires_at'       => now()->addMonths(4),
                'is_active'        => true,
            ],
            [
                'code'             => 'NEWYEAR30',
                'description'      => 'عرض رأس السنة - خصم 30%',
                'type'             => 'percentage',
                'value'            => 30,
                'free_shipping'    => false,
                'min_order_amount' => 150,
                'usage_limit'      => 40,
                'used_count'       => 0,
                'expires_at'       => now()->addMonths(2),
                'is_active'        => true,
            ],
            [
                'code'             => 'FLASH40',
                'description'      => 'خصم فلاش - 40% لفترة محدودة',
                'type'             => 'percentage',
                'value'            => 40,
                'free_shipping'    => false,
                'min_order_amount' => 200,
                'usage_limit'      => 20,
                'used_count'       => 0,
                'expires_at'       => now()->addMonths(1),
                'is_active'        => true,
            ],
            [
                'code'             => 'LOYALTY15',
                'description'      => 'خصم العملاء الدائمين - 15%',
                'type'             => 'percentage',
                'value'            => 15,
                'free_shipping'    => false,
                'min_order_amount' => 0,
                'usage_limit'      => 999,
                'used_count'       => 0,
                'expires_at'       => now()->addMonths(6),
                'is_active'        => true,
            ],
            [
                'code'             => 'VIP50',
                'description'      => 'خصم VIP - 50 شيكل',
                'type'             => 'fixed',
                'value'            => 50,
                'free_shipping'    => false,
                'min_order_amount' => 400,
                'usage_limit'      => 15,
                'used_count'       => 0,
                'expires_at'       => now()->addMonths(3),
                'is_active'        => true,
            ],
            [
                'code'             => 'RAMADAN20',
                'description'      => 'عرض رمضان - خصم 20%',
                'type'             => 'percentage',
                'value'            => 20,
                'free_shipping'    => false,
                'min_order_amount' => 100,
                'usage_limit'      => 200,
                'used_count'       => 0,
                'expires_at'       => now()->addMonths(2),
                'is_active'        => true,
            ],
            [
                'code'             => 'FIRST50',
                'description'      => 'خصم 50 شيكل على أول طلب',
                'type'             => 'fixed',
                'value'            => 50,
                'free_shipping'    => false,
                'min_order_amount' => 150,
                'usage_limit'      => 500,
                'used_count'       => 0,
                'expires_at'       => now()->addMonths(6),
                'is_active'        => true,
            ],
            [
                'code'             => 'FREESHIP200',
                'description'      => 'شحن مجاني للطلبات فوق 200 شيكل',
                'type'             => 'percentage',
                'value'            => 0,
                'free_shipping'    => true,
                'min_order_amount' => 200,
                'usage_limit'      => 300,
                'used_count'       => 0,
                'expires_at'       => now()->addMonths(3),
                'is_active'        => true,
            ],
        ];

        $added = 0;
        foreach ($coupons as $coupon) {
            if (Coupon::where('code', $coupon['code'])->exists()) {
                continue;
            }

            Coupon::create($coupon);
            $added++;
        }

        $this->command->line("   ✓ {$added} كوبون جديد");
        $this->command->line('   ℹ المجموع الآن: ' . Coupon::count());
    }
}