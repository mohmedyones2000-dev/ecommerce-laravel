<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $ordersQuery = Order::where('user_id', $user->id);

        $stats = [
            'total_orders'     => (clone $ordersQuery)->count(),
            'pending_orders'   => (clone $ordersQuery)->where('status', 'pending')->count(),
            'delivered_orders' => (clone $ordersQuery)->where('status', 'delivered')->count(),
            'wishlist_count'   => $user->wishlists()->count(),
        ];

        $recentOrders = (clone $ordersQuery)
            ->with(['items.variant.product.images'])
            ->latest()
            ->take(5)
            ->get();

        return view('dashboard', compact('user', 'stats', 'recentOrders'));
    }
}