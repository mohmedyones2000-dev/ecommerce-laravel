<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Wishlist;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
    public function index()
    {
        $wishlists = Wishlist::where('user_id', Auth::id())
            ->with(['product.images', 'product.category', 'product.variants'])
            ->latest()
            ->get();

        return view('wishlist.index', compact('wishlists'));
    }

    public function toggle(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
        ]);

        $deleted = Wishlist::where('user_id', Auth::id())
            ->where('product_id', $request->product_id)
            ->delete();

        if ($deleted) {
            $product = Product::find($request->product_id);

            NotificationService::wishlistRemoved(
                Auth::id(),
                $product->name ?? 'المنتج'
            );

            return response()->json([
                'status'      => 'removed',
                'message'     => 'تم الحذف من المفضلة',
                'in_wishlist' => false,
            ]);
        }

        Wishlist::create([
            'user_id'    => Auth::id(),
            'product_id' => $request->product_id,
        ]);

        $product = Product::find($request->product_id);

        NotificationService::wishlistAdded(
            Auth::id(),
            $product->name ?? 'المنتج'
        );

        return response()->json([
            'status'      => 'added',
            'message'     => 'تمت الإضافة إلى المفضلة',
            'in_wishlist' => true,
        ]);
    }

    public function remove(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
        ]);

        Wishlist::where('user_id', Auth::id())
            ->where('product_id', $request->product_id)
            ->delete();

        return redirect()->back()->with('success', 'تم الحذف من المفضلة');
    }
}