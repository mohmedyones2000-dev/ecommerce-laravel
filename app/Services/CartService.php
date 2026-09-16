<?php

namespace App\Services;

use App\Models\CartItem;
use App\Models\City;
use App\Models\Coupon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class CartService
{
    protected static ?array $cachedItems = null;
    protected static ?float $cachedTotal = null;
    protected static ?Coupon $cachedCoupon = null;
    protected static ?int $cachedCount = null;

    public static function add(int $variantId, int $quantity = 1): void
    {
        $item = CartItem::where('user_id', Auth::id())
            ->where('product_variant_id', $variantId)
            ->first();

        if ($item) {
            $item->quantity += $quantity;
            $item->save();
        } else {
            CartItem::create([
                'user_id'            => Auth::id(),
                'product_variant_id' => $variantId,
                'quantity'           => $quantity,
            ]);
        }

        self::clearCache();
    }

    public static function quantityInCart(int $variantId): int
    {
        foreach (self::getItems() as $item) {
            if ($item['variant_id'] === $variantId) {
                return $item['quantity'];
            }
        }

        return 0;
    }

    public static function getItems(): array
    {
        if (self::$cachedItems !== null) {
            return self::$cachedItems;
        }

        self::$cachedItems = CartItem::where('user_id', Auth::id())
            ->with(['variant.product.images'])
            ->get()
            ->map(fn ($item) => [
                'id'         => $item->id,
                'variant_id' => $item->product_variant_id,
                'quantity'   => $item->quantity,
                'variant'    => $item->variant,
                'product'    => $item->variant->product ?? null,
            ])
            ->toArray();

        return self::$cachedItems;
    }

    public static function update(int $variantId, int $quantity): void
    {
        if ($quantity <= 0) {
            self::remove($variantId);
            return;
        }

        CartItem::where('user_id', Auth::id())
            ->where('product_variant_id', $variantId)
            ->update(['quantity' => $quantity]);

        self::clearCache();
    }

    public static function remove(int $variantId): void
    {
        CartItem::where('user_id', Auth::id())
            ->where('product_variant_id', $variantId)
            ->delete();

        self::clearCache();
    }

    public static function clear(): void
    {
        CartItem::where('user_id', Auth::id())->delete();
        self::clearCache();
    }

    public static function total(): float
    {
        if (self::$cachedTotal !== null) {
            return self::$cachedTotal;
        }

        $total = 0;
        foreach (self::getItems() as $item) {
            if (!$item['product']) {
                continue;
            }

            $price = $item['product']->discount_price ?? $item['product']->price ?? 0;
            $total += $price * $item['quantity'];
        }

        self::$cachedTotal = $total;
        return $total;
    }

    public static function count(): int
    {
        if (!Auth::check()) {
            return 0;
        }

        if (self::$cachedCount !== null) {
            return self::$cachedCount;
        }

        self::$cachedCount = (int) CartItem::where('user_id', Auth::id())->sum('quantity');

        return self::$cachedCount;
    }

    public static function applyCoupon(string $code): array
    {
        $coupon = Coupon::where('code', strtoupper($code))->first();

        if (!$coupon) {
            return ['success' => false, 'message' => 'كود الخصم غير صحيح'];
        }

        if (!$coupon->isValid()) {
            return ['success' => false, 'message' => 'كود الخصم منتهي الصلاحية أو غير متاح'];
        }

        $total = self::total();

        if ($total < $coupon->min_order_amount) {
            return [
                'success' => false,
                'message' => 'الحد الأدنى للطلب لاستخدام هذا الكوبون هو $' . number_format($coupon->min_order_amount, 2),
            ];
        }

        Session::put('coupon_id', $coupon->id);
        Session::put('coupon_code', $coupon->code);

        self::$cachedCoupon = $coupon;

        return [
            'success'  => true,
            'message'  => 'تم تطبيق الكوبون بنجاح',
            'discount' => $coupon->calculateDiscount($total),
        ];
    }

    public static function removeCoupon(): void
    {
        Session::forget('coupon_id');
        Session::forget('coupon_code');
        self::$cachedCoupon = null;
    }

    public static function getCoupon(): ?Coupon
    {
        if (self::$cachedCoupon !== null) {
            return self::$cachedCoupon;
        }

        $couponId = Session::get('coupon_id');

        if (!$couponId) {
            return null;
        }

        $coupon = Coupon::find($couponId);

        if (!$coupon || !$coupon->isValid()) {
            self::removeCoupon();
            return null;
        }

        self::$cachedCoupon = $coupon;
        return $coupon;
    }

    public static function discount(): float
    {
        $coupon = self::getCoupon();

        if (!$coupon) {
            return 0;
        }

        return $coupon->calculateDiscount(self::total());
    }

    public static function finalTotal(): float
    {
        return max(0, self::total() - self::discount());
    }

    public static function shippingCost(?int $cityId = null): float
    {
        if (!$cityId) {
            $cityId = Session::get('selected_city_id');
        }

        if (!$cityId) {
            return 0;
        }

        $city = City::find($cityId);

        if (!$city) {
            return 0;
        }

        if ($city->hasFreeShipping()) {
            return 0;
        }

        $coupon = self::getCoupon();

        if ($coupon && $coupon->free_shipping) {
            return 0;
        }

        return (float) $city->shipping_cost;
    }

    public static function grandTotal(?int $cityId = null): float
    {
        return max(0, self::finalTotal() + self::shippingCost($cityId));
    }

    public static function setSelectedCity(?int $cityId): void
    {
        if ($cityId) {
            Session::put('selected_city_id', $cityId);
        } else {
            Session::forget('selected_city_id');
        }
    }

    public static function getSelectedCity(): ?City
    {
        $cityId = Session::get('selected_city_id');

        if (!$cityId) {
            return null;
        }

        return City::find($cityId);
    }

    public static function getSelectedCityId(): ?int
    {
        return Session::get('selected_city_id');
    }

    protected static function clearCache(): void
    {
        self::$cachedItems = null;
        self::$cachedTotal = null;
        self::$cachedCoupon = null;
        self::$cachedCount = null;
    }
}