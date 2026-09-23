<?php

namespace Database\Seeders;

use App\Models\Address;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\ProductVariant;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class OrderSeeder extends Seeder
{
    public function run(): void
    {
        $customers = User::where('role', 'customer')->get();
        $variants = ProductVariant::with('product')->get();

        if ($customers->isEmpty() || $variants->isEmpty()) {
            return;
        }

        $statuses = ['pending', 'processing', 'shipped', 'delivered', 'cancelled'];
        $paymentStatuses = ['unpaid', 'paid', 'paid', 'paid']; // ترجيح "مدفوع"

        foreach ($customers as $customer) {
            $address = Address::where('user_id', $customer->id)->first();
            if (!$address) continue;

            // ✅ 2-5 طلبات لكل زبون
            $orderCount = rand(2, 5);

            for ($i = 0; $i < $orderCount; $i++) {
                $status = $statuses[array_rand($statuses)];
                $paymentStatus = $paymentStatuses[array_rand($paymentStatuses)];

                $order = Order::create([
                    'order_number'   => 'ORD-' . strtoupper(Str::random(8)),
                    'user_id'        => $customer->id,
                    'address_id'     => $address->id,
                    'total_amount'   => 0, // سيتم حسابه
                    'shipping_cost'  => $address->city->shipping_cost ?? 15,
                    'status'         => $status,
                    'payment_status' => $paymentStatus,
                    'created_at'     => now()->subDays(rand(1, 60)),
                ]);

                // ✅ 1-4 عناصر لكل طلب
                $itemCount = rand(1, 4);
                $subtotal = 0;

                $randomVariants = $variants->random(min($itemCount, $variants->count()));

                foreach ($randomVariants as $variant) {
                    $quantity = rand(1, 3);
                    $unitPrice = $variant->price;
                    $totalPrice = $unitPrice * $quantity;
                    $subtotal += $totalPrice;

                    OrderItem::create([
                        'order_id'           => $order->id,
                        'product_variant_id' => $variant->id,
                        'quantity'           => $quantity,
                        'unit_price'         => $unitPrice,
                    ]);
                }

                // ✅ تحديث إجمالي الطلب
                $order->update([
                    'total_amount' => $subtotal + $order->shipping_cost,
                ]);
            }
        }

        $this->command->info('✅ Orders seeded: ' . Order::count() . ' orders, ' . OrderItem::count() . ' items');
    }
}