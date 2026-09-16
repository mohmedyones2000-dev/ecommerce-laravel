<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\City;
use App\Models\Order;
use App\Models\OrderItem;
use App\Services\CartService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    public function index()
    {
        $items = CartService::getItems();

        if (empty($items)) {
            return redirect()->route('cart.index')->with('error', 'سلتك فارغة');
        }

        $total = CartService::total();
        $discount = CartService::discount();
        $finalTotal = CartService::finalTotal();
        $coupon = CartService::getCoupon();
        $addresses = Address::where('user_id', Auth::id())->with('city')->get();
        $cities = City::orderBy('name')->get();

        $defaultCityId = $addresses->first()->city_id ?? null;
        CartService::setSelectedCity($defaultCityId);

        $shipping = CartService::shippingCost($defaultCityId);
        $grandTotal = CartService::grandTotal($defaultCityId);

        return view('checkout.index', compact(
            'items', 'total', 'discount', 'finalTotal', 'coupon',
            'addresses', 'cities', 'shipping', 'grandTotal'
        ));
    }

    public function getShipping(Request $request)
    {
        $request->validate([
            'city_id' => 'nullable|exists:cities,id',
            'address_id' => 'nullable|exists:addresses,id',
        ]);

        $cityId = $request->city_id;

        if (!$cityId && $request->address_id) {
            $address = Address::where('user_id', Auth::id())->find($request->address_id);
            $cityId = $address?->city_id;
        }

        CartService::setSelectedCity($cityId);

        return response()->json([
            'shipping'    => CartService::shippingCost($cityId),
            'grand_total' => CartService::grandTotal($cityId),
            'final_total' => CartService::finalTotal(),
        ]);
    }

    public function store(Request $request)
    {
        $items = CartService::getItems();

        if (empty($items)) {
            return redirect()->route('cart.index')->with('error', 'سلتك فارغة');
        }

        $request->validate([
            'address_option' => 'required|in:existing,new',
            'address_id'     => 'required_if:address_option,existing|nullable|exists:addresses,id',
            'street_address' => 'required_if:address_option,new|nullable|string|max:255',
            'city_id'        => 'required_if:address_option,new|nullable|exists:cities,id',
            'phone'          => 'required_if:address_option,new|nullable|string|max:20',
            'notes'          => 'nullable|string|max:255',
        ]);

        // 🔴 التحقق من المخزون قبل بدء المعاملة
        foreach ($items as $item) {
            $variant = $item['variant'];

            if (!$variant->product || !$variant->product->is_active) {
                return back()->with('error', 'أحد المنتجات في سلتك لم يعد متوفراً.');
            }

            if ($variant->stock_quantity < $item['quantity']) {
                return back()->with(
                    'error',
                    'الكمية المطلوبة من "' . $variant->product->name . '" غير متوفرة. المتاح: ' . $variant->stock_quantity
                );
            }
        }

        DB::beginTransaction();

        try {
            if ($request->address_option === 'existing') {
                $address = Address::where('user_id', Auth::id())->findOrFail($request->address_id);
            } else {
                $address = Address::create([
                    'user_id'        => Auth::id(),
                    'city_id'        => $request->city_id,
                    'street_address' => $request->street_address,
                    'phone'          => $request->phone,
                    'notes'          => $request->notes,
                ]);
            }

            $shippingCost = CartService::shippingCost($address->city_id);
            $totalAmount  = CartService::finalTotal() + $shippingCost;

            $order = Order::create([
                'order_number'   => 'ORD-' . strtoupper(Str::random(10)),
                'user_id'        => Auth::id(),
                'address_id'     => $address->id,
                'total_amount'   => $totalAmount,
                'shipping_cost'  => $shippingCost,
                'status'         => 'pending',
                'payment_status' => 'unpaid',
            ]);

            foreach ($items as $item) {
                OrderItem::create([
                    'order_id'           => $order->id,
                    'product_variant_id' => $item['variant_id'],
                    'quantity'           => $item['quantity'],
                    'unit_price'         => $item['product']->discount_price ?? $item['product']->price,
                ]);

                // خصم المخزون باستخدام decrement (لضمان الذرية)
                $item['variant']->decrement('stock_quantity', $item['quantity']);
            }

            $coupon = CartService::getCoupon();
            if ($coupon) {
                $coupon->increment('used_count');
            }

            CartService::clear();
            CartService::removeCoupon();
            CartService::setSelectedCity(null);

            DB::commit();

            return redirect()->route('checkout.success', $order->id);

        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Order creation failed: ' . $e->getMessage(), [
                'user_id' => Auth::id(),
                'trace'   => $e->getTraceAsString(),
            ]);

            return back()->with('error', 'حدث خطأ أثناء إتمام الطلب. يرجى المحاولة مرة أخرى.');
        }
    }

    public function success(Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        return view('checkout.success', compact('order'));
    }
}