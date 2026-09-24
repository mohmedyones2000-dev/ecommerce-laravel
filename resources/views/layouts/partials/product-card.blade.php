@php
    $primaryImages = $product->primary_images->take(2);
    $imageCount = $primaryImages->count();
    $totalStock = ($product->variants ?? collect())->sum('stock_quantity');
    $discountPercent = null;
    if ($product->discount_price && $product->price > 0) {
        $discountPercent = round((($product->price - $product->discount_price) / $product->price) * 100);
    }
@endphp

<article class="product-tile">
    <a href="{{ route('products.show', $product) }}" class="block">
        <div class="product-tile-media" style="border-radius: 4px;">
            @if($imageCount > 0)
                <img src="{{ $primaryImages[0]->image_url }}" alt="{{ $product->name }}" loading="lazy">
                @if($imageCount > 1)
                    <img src="{{ $primaryImages[1]->image_url }}" alt=""
                        class="absolute inset-0 opacity-0 transition-opacity duration-500 group-hover:opacity-100"
                        loading="lazy">
                @endif
            @else
                <div class="w-full h-full flex items-center justify-center opacity-30">
                    <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                    </svg>
                </div>
            @endif

            @if($discountPercent)
                <span class="absolute top-3 right-3 text-white text-[10px] font-bold tracking-widest px-2.5 py-1"
                    style="border-radius: 2px; background-color: #14b8a6;">
                    −{{ $discountPercent }}%
                </span>
            @endif

            @if($totalStock <= 0)
                <div class="absolute inset-0 bg-black/50 backdrop-blur-[1px] flex items-center justify-center">
                    <span class="text-white text-[10px] font-bold tracking-[0.2em] uppercase">نفذ المخزون</span>
                </div>
            @elseif($totalStock <= 5)
                <span class="absolute top-3 left-3 text-[10px] font-semibold tracking-widest">{{ $totalStock }} فقط</span>
            @endif
        </div>

        <div class="pt-5 pb-2">
            <p class="text-[10px] font-medium uppercase tracking-[0.15em] opacity-50 mb-2">
                {{ $product->category->name ?? 'منتج' }}
            </p>
            <h3 class="text-base font-semibold leading-snug line-clamp-2 group-hover:opacity-70 transition-opacity">
                {{ $product->name }}
            </h3>
            <div class="flex items-baseline gap-3 mt-3">
                @if($product->discount_price)
                    <span class="font-bold text-lg"
                        style="color: #14b8a6;">${{ number_format($product->discount_price, 2) }}</span>
                    <span class="text-xs line-through opacity-40">${{ number_format($product->price, 2) }}</span>
                @else
                    <span class="font-bold text-lg">${{ number_format($product->price, 2) }}</span>
                @endif
            </div>
        </div>
    </a>

    @auth
        @php
            $inWishlist = \App\Models\Wishlist::where('user_id', auth()->id())->where('product_id', $product->id)->exists();
        @endphp
        <button type="button" data-product-id="{{ $product->id }}"
            onclick="event.preventDefault(); event.stopPropagation(); toggleWishlist(this)"
            class="absolute top-3 left-3 w-8 h-8 flex items-center justify-center transition-all {{ $inWishlist ? '' : 'opacity-0 group-hover:opacity-100' }}"
            style="color: {{ $inWishlist ? '#14b8a6' : '' }};" title="المفضلة">
            <svg class="w-4 h-4 wishlist-icon" fill="{{ $inWishlist ? 'currentColor' : 'none' }}" stroke="currentColor"
                viewBox="0 0 24 24" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
            </svg>
        </button>
    @endauth
</article>