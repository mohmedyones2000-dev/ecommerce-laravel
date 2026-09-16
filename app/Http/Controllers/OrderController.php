<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::where('user_id', Auth::id())
            ->withCount('items')
            ->latest()
            ->paginate(10);

        return view('orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        $order->load(['items.variant.product.images', 'address.city']);

        $statusSteps = ['pending', 'processing', 'shipped', 'delivered'];
        $currentStep = array_search($order->status, $statusSteps, true);

        if ($currentStep === false) {
            $currentStep = -1;
            $progress = 0;
        } else {
            $progress = (($currentStep + 1) / count($statusSteps)) * 100;
        }

        return view('orders.show', compact('order', 'statusSteps', 'currentStep', 'progress'));
    }
}