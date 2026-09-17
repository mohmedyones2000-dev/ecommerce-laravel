@php
    $primaryImages = $product->primary_images->take(7);
    $imageCount = $primaryImages->count();
    $hasMultipleImages = $imageCount > 1;
    $totalStock = ($product->variants ?? collect())->sum('stock_quantity');
@endphp

<div class="group relative bg-white rounded-xl border overflow-hidden transition-all duration-200 hover:-translate-y-0.5 hover:shadow-lg"
    style="border-color: var(--border-light);">

    <a href="{{ route('products.show', $product) }}" class="block">
        <div class="aspect-square relative overflow-hidden product-image-stack"
            style="background-color: var(--bg-tertiary);" data-image-count="{{ $imageCount }}">

            @if($imageCount > 0)
                @foreach($primaryImages as $index => $image)
                    <img src="{{ asset('storage/' . $image->image_path) }}" alt="{{ $product->name }}" class="product-stack-img"
                        data-index="{{ $index }}" style="z-index: {{ $imageCount - $index }};">
                @endforeach
            @else
                <div class="w-full h-full flex items-center justify-center" style="color: var(--text-tertiary);">
                    <svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                            d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                    </svg>
                </div>
            @endif

            @if($product->discount_price)
                <span class="absolute top-2.5 right-2.5 text-white text-[10px] font-bold px-2 py-1 rounded z-50"
                    style="background-color: #dc2626;">
                    {{ round((($product->price - $product->discount_price) / $product->price) * 100) }}-%
                </span>
            @endif

            @if($totalStock > 0 && $totalStock <= 5)
                <span class="absolute top-2.5 left-2.5 text-white text-[10px] font-bold px-2 py-1 rounded z-50"
                    style="background-color: #ea580c;">
                    بقي {{ $totalStock }}
                </span>
            @endif

            @if($imageCount > 1)
                <div
                    class="absolute bottom-2.5 left-1/2 -translate-x-1/2 flex gap-1 z-50 opacity-0 group-hover:opacity-100 transition-opacity">
                    @for($i = 0; $i < $imageCount; $i++)
                        <span class="image-dot w-1.5 h-1.5 rounded-full transition-all duration-200" data-dot-index="{{ $i }}"
                            style="background-color: rgba(255,255,255,0.5);"></span>
                    @endfor
                </div>
            @endif
        </div>
    </a>

    @auth
        @php
            $inWishlist = \App\Models\Wishlist::where('user_id', auth()->id())
                ->where('product_id', $product->id)->exists();
        @endphp
        <button type="button" data-wishlist-btn data-product-id="{{ $product->id }}"
            data-in-wishlist="{{ $inWishlist ? '1' : '0' }}"
            onclick="event.preventDefault(); event.stopPropagation(); toggleWishlist(this)"
            title="{{ $inWishlist ? 'حذف من المفضلة' : 'إضافة إلى المفضلة' }}" class="absolute z-40 w-8 h-8 rounded-lg flex items-center justify-center transition-all duration-200
                           {{ $inWishlist ? 'opacity-100' : 'opacity-0 group-hover:opacity-100' }}" style="top: 10px; inset-inline-end: 10px;
                           background-color: {{ $inWishlist ? '#fee2e2' : 'rgba(255,255,255,0.95)' }};
                           color: {{ $inWishlist ? '#dc2626' : 'var(--text-tertiary)' }};">
            <svg class="w-4 h-4 wishlist-icon" fill="{{ $inWishlist ? 'currentColor' : 'none' }}" stroke="currentColor"
                viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
            </svg>
        </button>
    @endauth

    <div class="p-3.5">
        <p class="text-[11px] mb-1 truncate" style="color: var(--text-tertiary);">
            {{ $product->category->name ?? 'بدون تصنيف' }}
        </p>

        <a href="{{ route('products.show', $product) }}" class="block">
            <h3 class="text-[13px] font-medium leading-snug mb-2 line-clamp-2 transition-colors duration-150"
                style="color: var(--text-primary);" onmouseover="this.style.color='var(--gold)';"
                onmouseout="this.style.color='var(--text-primary)';">
                {{ $product->name }}
            </h3>
        </a>

        <div class="flex items-center justify-between gap-2 mt-3">
            <div class="flex items-baseline gap-1.5 min-w-0">
                @if($product->discount_price)
                    <span class="text-[15px] font-bold" style="color: var(--gold);">
                        ${{ number_format($product->discount_price, 2) }}
                    </span>
                    <span class="text-[11px] line-through" style="color: var(--text-tertiary);">
                        ${{ number_format($product->price, 2) }}
                    </span>
                @else
                    <span class="text-[15px] font-bold" style="color: var(--gold);">
                        ${{ number_format($product->price, 2) }}
                    </span>
                @endif
            </div>

            @auth
                @php
                    $inStockVariants = ($product->variants ?? collect())->where('stock_quantity', '>', 0);
                @endphp

                @if($inStockVariants->count() === 1)
                    <form action="{{ route('cart.add') }}" method="POST" class="inline">
                        @csrf
                        <input type="hidden" name="variant_id" value="{{ $inStockVariants->first()->id }}">
                        <input type="hidden" name="quantity" value="1">
                        <button type="submit" title="أضف إلى السلة"
                            class="w-8 h-8 rounded-lg flex items-center justify-center text-white transition-all duration-150 hover:opacity-90"
                            style="background-color: var(--gold);">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                        </button>
                    </form>
                @elseif($inStockVariants->count() > 1)
                    <a href="{{ route('products.show', $product) }}" title="اختر المتغير"
                        class="w-8 h-8 rounded-lg flex items-center justify-center transition-all duration-150"
                        style="background-color: var(--bg-tertiary); color: var(--text-secondary);"
                        onmouseover="this.style.backgroundColor='var(--gold)'; this.style.color='white';"
                        onmouseout="this.style.backgroundColor='var(--bg-tertiary)'; this.style.color='var(--text-secondary)';">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </a>
                @else
                    <span class="w-8 h-8 rounded-lg flex items-center justify-center cursor-not-allowed"
                        style="background-color: var(--bg-tertiary); color: var(--text-tertiary);">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </span>
                @endif
            @else
                <a href="{{ route('login') }}" title="سجّل دخولك"
                    class="w-8 h-8 rounded-lg flex items-center justify-center transition-all duration-150"
                    style="background-color: var(--bg-tertiary); color: var(--text-secondary);"
                    onmouseover="this.style.backgroundColor='var(--gold)'; this.style.color='white';"
                    onmouseout="this.style.backgroundColor='var(--bg-tertiary)'; this.style.color='var(--text-secondary)';">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                    </svg>
                </a>
            @endauth
        </div>
    </div>
</div>