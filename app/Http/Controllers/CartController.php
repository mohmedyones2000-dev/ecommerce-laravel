<?php

namespace App\Http\Controllers;

use App\Models\ProductVariant;
use App\Services\CartService;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        $items = CartService::getItems();

        if (empty($items)) {
            return view('cart.index', [
                'items'        => [],
                'total'        => 0,
                'discount'     => 0,
                'finalTotal'   => 0,
                'coupon'       => null,
                'shipping'     => 0,
                'grandTotal'   => 0,
                'selectedCity' => null,
            ]);
        }

        $selectedCityId = CartService::getSelectedCityId();

        return view('cart.index', [
            'items'        => $items,
            'total'        => CartService::total(),
            'discount'     => CartService::discount(),
            'finalTotal'   => CartService::finalTotal(),
            'coupon'       => CartService::getCoupon(),
            'shipping'     => CartService::shippingCost($selectedCityId),
            'grandTotal'   => CartService::grandTotal($selectedCityId),
            'selectedCity' => CartService::getSelectedCity(),
        ]);
    }

    public function add(Request $request)
    {
        $request->validate([
            'variant_id' => 'required|exists:product_variants,id',
            'quantity'   => 'required|integer|min:1|max:100',
        ]);

        $variant = ProductVariant::with('product')->find($request->variant_id);

        if (!$variant->product || !$variant->product->is_active) {
            return back()->with('error', 'هذا المنتج لم يعد متوفراً.');
        }

        $currentInCart = CartService::quantityInCart($variant->id);
        $requested = $currentInCart + $request->quantity;

        if ($requested > $variant->stock_quantity) {
            return back()->with(
                'error',
                'الكمية المطلوبة غير متوفرة. المتاح: ' . $variant->stock_quantity
            );
        }

        CartService::add($request->variant_id, $request->quantity);

        return back()->with('success', 'تمت إضافة المنتج إلى السلة');
    }

    public function update(Request $request)
    {
        $request->validate([
            'variant_id' => 'required|exists:product_variants,id',
            'quantity'   => 'required|integer|min:1|max:100',
        ]);

        $variant = ProductVariant::find($request->variant_id);

        if ($request->quantity > $variant->stock_quantity) {
            return back()->with(
                'error',
                'الكمية المطلوبة غير متوفرة. المتاح: ' . $variant->stock_quantity
            );
        }

        CartService::update($request->variant_id, $request->quantity);

        return redirect()->route('cart.index')->with('success', 'تم تحديث السلة');
    }

    public function remove(Request $request)
    {
        $request->validate([
            'variant_id' => 'required|exists:product_variants,id',
        ]);

        CartService::remove($request->variant_id);

        return redirect()->route('cart.index')->with('success', 'تم حذف المنتج من السلة');
    }
}