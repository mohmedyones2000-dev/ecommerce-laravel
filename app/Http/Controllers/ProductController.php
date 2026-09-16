<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Color;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::where('is_active', true)
            ->with(['images', 'category:id,name', 'brand:id,name', 'variants']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($request->filled('category')) {
            $query->whereHas('category', fn ($q) => $q->where('slug', $request->category));
        }

        if ($request->filled('brand')) {
            $brandIds = is_array($request->brand) ? $request->brand : [$request->brand];
            $query->whereIn('brand_id', $brandIds);
        }

        if ($request->filled('min_price')) {
            $query->where(function ($q) use ($request) {
                $q->where('discount_price', '>=', $request->min_price)
                  ->orWhere(function ($q2) use ($request) {
                      $q2->whereNull('discount_price')
                         ->where('price', '>=', $request->min_price);
                  });
            });
        }

        if ($request->filled('max_price')) {
            $query->where(function ($q) use ($request) {
                $q->where('discount_price', '<=', $request->max_price)
                  ->orWhere(function ($q2) use ($request) {
                      $q2->whereNull('discount_price')
                         ->where('price', '<=', $request->max_price);
                  });
            });
        }

        if ($request->filled('min_rating')) {
            $minRating = $request->min_rating;
            $query->whereHas('reviews', function ($q) use ($minRating) {
                $q->selectRaw('product_id, AVG(rating) as avg_rating')
                  ->groupBy('product_id')
                  ->havingRaw('AVG(rating) >= ?', [$minRating]);
            });
        }

        if ($request->filled('in_stock') && $request->in_stock == '1') {
            $query->whereHas('variants', fn ($q) => $q->where('stock_quantity', '>', 0));
        }

        if ($request->filled('has_discount') && $request->has_discount == '1') {
            $query->whereNotNull('discount_price')->where('discount_price', '>', 0);
        }

        switch ($request->get('sort', 'latest')) {
            case 'price_low':
                $query->orderByRaw('COALESCE(discount_price, price) ASC');
                break;
            case 'price_high':
                $query->orderByRaw('COALESCE(discount_price, price) DESC');
                break;
            case 'rating':
                $query->withAvg('reviews', 'rating')->orderByDesc('reviews_avg_rating');
                break;
            case 'popular':
                $query->withCount('orderItems')->orderByDesc('order_items_count');
                break;
            default:
                $query->latest();
                break;
        }

        $products = $query->paginate(12)->withQueryString();
        $categories = Category::all();
        $brands = Brand::has('products')->get();

        $maxPrice = Product::where('is_active', true)
            ->selectRaw('MAX(COALESCE(discount_price, price)) as max_price')
            ->value('max_price') ?? 1000;

        return view('products.index', compact('products', 'categories', 'brands', 'maxPrice'));
    }

    public function show(Product $product)
    {
        $this->trackView($product->id);

        $product->load([
            'images',
            'variants',
            'reviews.user:id,name',
            'category:id,name',
            'brand:id,name',
            'sizeGuide.items',
        ]);

        $relatedProducts = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->where('is_active', true)
            ->with(['images', 'category', 'variants'])
            ->take(4)
            ->get();

        $recentlyViewed = $this->getRecentlyViewed($product->id);

        $colorsMap = Color::active()->pluck('hex_code', 'name')->toArray();

        $variantsData = [];
        foreach ($product->variants as $v) {
            $variantsData[] = [
                'id'       => $v->id,
                'color'    => $v->color ?: 'بدون لون',
                'hex_code' => $v->hex_code ?: ($colorsMap[$v->color] ?? '#cccccc'),
                'size'     => $v->size ?: 'بدون مقاس',
                'stock'    => (int) $v->stock_quantity,
            ];
        }

        $uniqueColorsData = [];
        $uniqueColorNames = [];

        foreach ($product->variants->groupBy('color') as $colorName => $variants) {
            $name = $colorName ?: 'بدون لون';
            $firstVariant = $variants->first();

            $uniqueColorsData[] = [
                'color' => $name,
                'hex'   => $firstVariant->hex_code ?: ($colorsMap[$name] ?? '#cccccc'),
            ];
            $uniqueColorNames[] = $name;
        }

        return view('products.show', [
            'product'          => $product,
            'relatedProducts'  => $relatedProducts,
            'recentlyViewed'   => $recentlyViewed,
            'variantsData'     => $variantsData,
            'uniqueColorsData' => $uniqueColorsData,
            'uniqueColorNames' => $uniqueColorNames,
        ]);
    }

    protected function trackView(int $productId): void
    {
        $viewed = session()->get('recently_viewed', []);
        $viewed = array_diff($viewed, [$productId]);
        array_unshift($viewed, $productId);
        $viewed = array_slice($viewed, 0, 10);
        session()->put('recently_viewed', $viewed);
    }

    protected function getRecentlyViewed(?int $excludeId = null, int $limit = 6): Collection
    {
        $viewed = session()->get('recently_viewed', []);

        if (empty($viewed)) {
            return collect();
        }

        if ($excludeId) {
            $viewed = array_diff($viewed, [$excludeId]);
        }

        if (empty($viewed)) {
            return collect();
        }

        return Product::whereIn('id', $viewed)
            ->where('is_active', true)
            ->with(['images', 'category', 'variants'])
            ->get()
            ->sortBy(fn ($product) => array_search($product->id, $viewed))
            ->take($limit);
    }

    public function recentlyViewed()
    {
        $viewed = session()->get('recently_viewed', []);

        if (empty($viewed)) {
            return view('products.recently-viewed', ['products' => collect()]);
        }

        $products = Product::whereIn('id', $viewed)
            ->where('is_active', true)
            ->with(['images', 'category', 'variants'])
            ->get()
            ->sortBy(fn ($product) => array_search($product->id, $viewed));

        return view('products.recently-viewed', compact('products'));
    }

    public function search(Request $request)
    {
        $query = trim($request->get('q', ''));

        if (mb_strlen($query) < 2) {
            return response()->json(['products' => [], 'total' => 0]);
        }

        $searchFilter = function ($q) use ($query) {
            $q->where('name', 'like', "%{$query}%")
              ->orWhere('description', 'like', "%{$query}%");
        };

        $products = Product::where('is_active', true)
            ->where($searchFilter)
            ->with('images')
            ->latest()
            ->take(6)
            ->get()
            ->map(fn ($product) => [
                'id'             => $product->id,
                'name'           => $product->name,
                'slug'           => $product->slug,
                'price'          => $product->discount_price ?? $product->price,
                'original_price' => $product->discount_price ? $product->price : null,
                'image'          => $product->images->first()
                    ? asset('storage/' . $product->images->first()->image_path)
                    : null,
                'url'            => route('products.show', $product),
            ]);

        $total = Product::where('is_active', true)->where($searchFilter)->count();

        return response()->json([
            'products' => $products,
            'total'    => $total,
        ]);
    }
}