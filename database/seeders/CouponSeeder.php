<?php

namespace Database\Seeders;

use App\Models\Coupon;
use Illuminate\Database\Seeder;

class CouponSeeder extends Seeder
{
    public function run(): void
    {
        $coupons = [
            [
                'code'              => 'WELCOME10',
                'description'       => 'خصم ترحيبي 10% للعملاء الجدد',
                'type'              => 'percentage',
                'value'             => 10,
                'free_shipping'     => false,
                'min_order_amount'  => 100,
                'usage_limit'       => 100,
                'used_count'        => 0,
                'expires_at'        => now()->addMonths(3),
                'is_active'         => true,
            ],
            [
                'code'              => 'SAVE50',
                'description'       => 'خصم 50 شيكل على الطلبات فوق 300',
                'type'              => 'fixed',
                'value'             => 50,
                'free_shipping'     => false,
                'min_order_amount'  => 300,
                'usage_limit'       => 50,
                'used_count'        => 0,
                'expires_at'        => now()->addMonth(),
                'is_active'         => true,
            ],
            [
                'code'              => 'FREESHIP',
                'description'       => 'شحن مجاني على جميع الطلبات',
                'type'              => 'percentage',
                'value'             => 0,
                'free_shipping'     => true,
                'min_order_amount'  => 0,
                'usage_limit'       => null,
                'used_count'        => 0,
                'expires_at'        => now()->addMonths(2),
                'is_active'         => true,
            ],
            [
                'code'              => 'VIP20',
                'description'       => 'خصم VIP 20% على الطلبات فوق 200',
                'type'              => 'percentage',
                'value'             => 20,
                'free_shipping'     => false,
                'min_order_amount'  => 200,
                'usage_limit'       => 20,
                'used_count'        => 0,
                'expires_at'        => now()->addMonths(2),
                'is_active'         => true,
            ],
            [
                'code'              => 'SUMMER30',
                'description'       => 'تخفيضات الصيف 30%',
                'type'              => 'percentage',
                'value'             => 30,
                'free_shipping'     => false,
                'min_order_amount'  => 150,
                'usage_limit'       => 200,
                'used_count'        => 0,
                'expires_at'        => now()->addMonths(1),
                'is_active'         => true,
            ],
        ];

        foreach ($coupons as $coupon) {
            Coupon::updateOrCreate(
                ['code' => $coupon['code']],
                $coupon
            );
        }

        $this->command->info('✅ Coupons seeded: ' . Coupon::count() . ' coupons');
    }
}