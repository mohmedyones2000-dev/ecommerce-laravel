<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $featuredProducts = Product::where('is_featured', true)
            ->where('is_active', true)
            ->with(['images', 'category', 'variants'])
            ->latest()
            ->take(8)
            ->get();

        $latestProducts = Product::where('is_active', true)
            ->with(['images', 'category', 'variants'])
            ->latest()
            ->take(8)
            ->get();

        $bestSellers = Product::where('is_active', true)
            ->withCount('orderItems')
            ->having('order_items_count', '>', 0)
            ->orderByDesc('order_items_count')
            ->with(['images', 'category', 'variants'])
            ->take(4)
            ->get();

        $topRated = Product::where('is_active', true)
            ->withAvg('reviews', 'rating')
            ->having('reviews_avg_rating', '>=', 3)
            ->orderByDesc('reviews_avg_rating')
            ->with(['images', 'category', 'variants'])
            ->take(4)
            ->get();

        $categories = Category::with('subCategories')->take(6)->get();

        $recentlyViewed = collect();
        $viewed = session()->get('recently_viewed', []);

        if (!empty($viewed)) {
            $recentlyViewed = Product::whereIn('id', $viewed)
                ->where('is_active', true)
                ->with(['images', 'category', 'variants'])
                ->get()
                ->sortBy(fn ($product) => array_search($product->id, $viewed))
                ->take(4);
        }

        return view('home', compact(
            'featuredProducts',
            'latestProducts',
            'bestSellers',
            'topRated',
            'categories',
            'recentlyViewed'
        ));
    }
}