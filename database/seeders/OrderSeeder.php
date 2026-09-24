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
        $this->command->info('→ إضافة 400 طلب على 6 شهور...');

        $customers = User::where('role', 'customer')->get();
        $variants = ProductVariant::with('product')->get();

        if ($customers->isEmpty() || $variants->isEmpty()) {
            $this->command->error('   ✗ بيانات ناقصة');
            return;
        }

        // توزيع واقعي للحالات
        $statusPool = array_merge(
            array_fill(0, 15, 'pending'),
            array_fill(0, 20, 'processing'),
            array_fill(0, 15, 'shipped'),
            array_fill(0, 45, 'delivered'),
            array_fill(0, 5,  'cancelled')
        );

        $paymentPool = array_merge(
            array_fill(0, 20, 'unpaid'),
            array_fill(0, 75, 'paid'),
            array_fill(0, 5,  'refunded')
        );

        $created = 0;
        $itemsCreated = 0;

        for ($i = 0; $i < 400; $i++) {
            // تاريخ عشوائي خلال 180 يوم
            $daysAgo = rand(0, 180);
            $createdAt = now()
                ->subDays($daysAgo)
                ->subHours(rand(0, 23))
                ->subMinutes(rand(0, 59));

            $customer = $customers->random();
            $address = Address::where('user_id', $customer->id)->first();

            if (!$address) {
                continue;
            }

            try {
                $order = Order::create([
                    'order_number'   => 'ORD-' . strtoupper(Str::random(8)),
                    'user_id'        => $customer->id,
                    'address_id'     => $address->id,
                    'total_amount'   => 0,
                    'shipping_cost'  => $address->city->shipping_cost ?? rand(10, 30),
                    'status'         => $statusPool[array_rand($statusPool)],
                    'payment_status' => $paymentPool[array_rand($paymentPool)],
                    'created_at'     => $createdAt,
                    'updated_at'     => $createdAt,
                ]);

                // 1 إلى 4 عناصر لكل طلب
                $itemCount = rand(1, 4);
                $subtotal = 0;
                $usedVariantIds = [];

                for ($j = 0; $j < $itemCount; $j++) {
                    $variant = $variants->random();

                    if (in_array($variant->id, $usedVariantIds)) {
                        continue;
                    }
                    $usedVariantIds[] = $variant->id;

                    $product = $variant->product;
                    if (!$product) {
                        continue;
                    }

                    // السعر من product (مع دعم discount_price)
                    $unitPrice = $product->discount_price ?? $product->price;
                    $quantity = rand(1, 3);
                    $lineTotal = $unitPrice * $quantity;

                    OrderItem::create([
                        'order_id'           => $order->id,
                        'product_variant_id' => $variant->id,
                        'quantity'           => $quantity,
                        'unit_price'         => $unitPrice,
                        'created_at'         => $createdAt,
                        'updated_at'         => $createdAt,
                    ]);

                    $subtotal += $lineTotal;
                    $itemsCreated++;
                }

                $order->update([
                    'total_amount' => $subtotal + $order->shipping_cost,
                ]);

                $created++;
            } catch (\Exception $e) {
                // تجاهل التكرار
            }
        }

        $this->command->line("   ✓ {$created} طلب جديد");
        $this->command->line("   ✓ {$itemsCreated} عنصر طلب");
        $this->command->line('   ℹ المجموع الآن: ' . Order::count() . ' طلب');
    }
}