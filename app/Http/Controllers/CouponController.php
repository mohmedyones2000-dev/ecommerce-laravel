<?php

namespace App\Http\Controllers;

use App\Services\CartService;
use Illuminate\Http\Request;

class CouponController extends Controller
{
    public function apply(Request $request)
    {
        $request->validate([
            'code' => 'required|string|max:50',
        ]);

        $result = CartService::applyCoupon($request->code);

        if (!$result['success']) {
            return back()->with('error', $result['message']);
        }

        return back()->with('success', $result['message']);
    }

    public function remove()
    {
        CartService::removeCoupon();

        return back()->with('success', 'تم إزالة الكوبون');
    }
}