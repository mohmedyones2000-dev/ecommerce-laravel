@extends('layouts.app')

@section('title', $product->name . ' | متجري')

@section('content')

    <div class="max-w-[1400px] mx-auto px-4 sm:px-6 lg:px-8 py-8">

        <nav class="flex items-center gap-2 text-[12px] mb-6 flex-wrap" style="color: var(--text-tertiary);">
            <a href="{{ route('home') }}" class="transition-colors duration-150 hover:text-[color:var(--gold)]">الرئيسية</a>
            <span>/</span>
            <a href="{{ route('products.index') }}"
                class="transition-colors duration-150 hover:text-[color:var(--gold)]">المنتجات</a>
            <span>/</span>
            <a href="{{ route('products.index', ['category' => $product->category->slug ?? '']) }}"
                class="transition-colors duration-150 hover:text-[color:var(--gold)]">
                {{ $product->category->name ?? '' }}
            </a>
            <span>/</span>
            <span style="color: var(--text-primary);">{{ $product->name }}</span>
        </nav>

        <div class="grid md:grid-cols-2 gap-10 mb-14">

            <div>
                @php
                    $firstColor = $product->variants->pluck('color')->filter()->first();
                    $firstColorKey = $firstColor ?: 'بدون لون';
                    $initialImages = $imagesByColor[$firstColorKey] ?? [];
                    if (empty($initialImages)) {
                        $initialImages = collect($imagesByColor)->flatten(1)->take(7)->values()->toArray();
                    }
                @endphp

                <div class="aspect-square rounded-xl overflow-hidden mb-3 relative group product-gallery"
                    style="background-color: var(--bg-tertiary);">

                    @if(!empty($initialImages))
                        <img id="mainImage" src="{{ $initialImages[0]['path'] }}" alt="{{ $product->name }}"
                            class="w-full h-full object-cover transition-opacity duration-300">

                        <button type="button" onclick="openFullscreen(document.getElementById('mainImage').src)"
                            title="عرض الصورة كاملة"
                            class="absolute top-3 left-3 z-30 w-10 h-10 rounded-lg flex items-center justify-center transition-all duration-200 opacity-0 group-hover:opacity-100"
                            style="background-color: rgba(0, 0, 0, 0.6); color: white; backdrop-filter: blur(8px);"
                            onmouseover="this.style.backgroundColor='rgba(0, 0, 0, 0.85)';"
                            onmouseout="this.style.backgroundColor='rgba(0, 0, 0, 0.6)';">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4" />
                            </svg>
                        </button>

                        <button type="button" onclick="prevProductImage()" id="prevImageBtn" title="السابق"
                            class="absolute top-1/2 -translate-y-1/2 right-3 z-20 w-10 h-10 rounded-lg flex items-center justify-center transition-all duration-200 opacity-0 group-hover:opacity-100"
                            style="background-color: rgba(0, 0, 0, 0.5); color: white; backdrop-filter: blur(8px);"
                            onmouseover="this.style.backgroundColor='rgba(0, 0, 0, 0.8)';"
                            onmouseout="this.style.backgroundColor='rgba(0, 0, 0, 0.5)';">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </button>

                        <button type="button" onclick="nextProductImage()" id="nextImageBtn" title="التالي"
                            class="absolute top-1/2 -translate-y-1/2 left-3 z-20 w-10 h-10 rounded-lg flex items-center justify-center transition-all duration-200 opacity-0 group-hover:opacity-100"
                            style="background-color: rgba(0, 0, 0, 0.5); color: white; backdrop-filter: blur(8px);"
                            onmouseover="this.style.backgroundColor='rgba(0, 0, 0, 0.8)';"
                            onmouseout="this.style.backgroundColor='rgba(0, 0, 0, 0.5)';">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                            </svg>
                        </button>

                        <div id="productImageDots" class="absolute bottom-3 left-1/2 -translate-x-1/2 flex gap-1.5 z-20">
                        </div>
                    @else
                        <div class="w-full h-full flex items-center justify-center" style="color: var(--text-tertiary);">
                            <svg class="w-24 h-24" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                                    d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                            </svg>
                        </div>
                    @endif
                </div>
            </div>

            <div>
                @if($product->brand)
                    <p class="text-[12px] font-semibold mb-2" style="color: var(--gold);">{{ $product->brand->name }}</p>
                @endif

                <h1 class="text-2xl md:text-3xl font-bold mb-4 leading-tight" style="color: var(--text-primary);">
                    {{ $product->name }}</h1>

                <div class="flex flex-wrap gap-2 mb-5">
                    @auth
                        @php
                            $inWishlist = \App\Models\Wishlist::where('user_id', auth()->id())
                                ->where('product_id', $product->id)->exists();
                        @endphp
                        <button type="button" data-wishlist-btn data-product-id="{{ $product->id }}"
                            data-in-wishlist="{{ $inWishlist ? '1' : '0' }}" onclick="toggleWishlist(this)"
                            class="inline-flex items-center gap-2 h-9 px-4 rounded-lg border text-[13px] font-medium transition-all duration-150"
                            style="border-color: {{ $inWishlist ? '#dc2626' : 'var(--border-light)' }}; color: {{ $inWishlist ? '#dc2626' : 'var(--text-secondary)' }}; background-color: {{ $inWishlist ? '#fef2f2' : 'var(--bg-primary)' }};">
                            <svg class="w-4 h-4 wishlist-icon" fill="{{ $inWishlist ? 'currentColor' : 'none' }}"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                    d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                            </svg>
                            <span class="wishlist-text">{{ $inWishlist ? 'في المفضلة' : 'أضف للمفضلة' }}</span>
                        </button>
                    @endauth

                    <div class="relative inline-block" x-data="{ shareOpen: false }">
                        <button type="button" @click="shareOpen = !shareOpen"
                            class="inline-flex items-center gap-2 h-9 px-4 rounded-lg border text-[13px] font-medium transition-all duration-150"
                            style="border-color: var(--border-light); color: var(--text-secondary); background-color: var(--bg-primary);"
                            onmouseover="this.style.borderColor='var(--gold)'; this.style.color='var(--gold)';"
                            onmouseout="this.style.borderColor='var(--border-light)'; this.style.color='var(--text-secondary)';">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                    d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z" />
                            </svg>
                            مشاركة
                        </button>

                        <div x-show="shareOpen" @click.away="shareOpen = false" x-cloak
                            x-transition:enter="transition ease-out duration-150"
                            x-transition:enter-start="opacity-0 -translate-y-1"
                            x-transition:enter-end="opacity-100 translate-y-0"
                            class="absolute top-full right-0 mt-2 w-56 rounded-xl border shadow-lg py-1 z-50"
                            style="background-color: var(--bg-primary); border-color: var(--border-light);">

                            <p class="px-4 py-2 text-[11px] font-semibold border-b"
                                style="color: var(--text-tertiary); border-color: var(--border-light);">
                                شارك المنتج عبر
                            </p>

                            <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(route('products.show', $product)) }}"
                                target="_blank"
                                class="flex items-center gap-3 px-4 py-2.5 hover:bg-gray-50 transition-colors">
                                <div class="w-7 h-7 bg-blue-500 rounded-lg flex items-center justify-center">
                                    <svg class="w-3.5 h-3.5 text-white" fill="currentColor" viewBox="0 0 24 24">
                                        <path
                                            d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z" />
                                    </svg>
                                </div>
                                <span class="text-[13px]" style="color: var(--text-primary);">فيسبوك</span>
                            </a>

                            <a href="https://wa.me/?text={{ urlencode($product->name . ' - ' . route('products.show', $product)) }}"
                                target="_blank"
                                class="flex items-center gap-3 px-4 py-2.5 hover:bg-gray-50 transition-colors">
                                <div class="w-7 h-7 bg-green-500 rounded-lg flex items-center justify-center">
                                    <svg class="w-3.5 h-3.5 text-white" fill="currentColor" viewBox="0 0 24 24">
                                        <path
                                            d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z" />
                                    </svg>
                                </div>
                                <span class="text-[13px]" style="color: var(--text-primary);">واتساب</span>
                            </a>

                            <a href="https://twitter.com/intent/tweet?text={{ urlencode($product->name) }}&url={{ urlencode(route('products.show', $product)) }}"
                                target="_blank"
                                class="flex items-center gap-3 px-4 py-2.5 hover:bg-gray-50 transition-colors">
                                <div class="w-7 h-7 bg-zinc-900 rounded-lg flex items-center justify-center">
                                    <svg class="w-3.5 h-3.5 text-white" fill="currentColor" viewBox="0 0 24 24">
                                        <path
                                            d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z" />
                                    </svg>
                                </div>
                                <span class="text-[13px]" style="color: var(--text-primary);">تويتر</span>
                            </a>

                            <a href="https://t.me/share/url?url={{ urlencode(route('products.show', $product)) }}&text={{ urlencode($product->name) }}"
                                target="_blank"
                                class="flex items-center gap-3 px-4 py-2.5 hover:bg-gray-50 transition-colors">
                                <div class="w-7 h-7 bg-sky-500 rounded-lg flex items-center justify-center">
                                    <svg class="w-3.5 h-3.5 text-white" fill="currentColor" viewBox="0 0 24 24">
                                        <path
                                            d="M11.944 0A12 12 0 0 0 0 12a12 12 0 0 0 12 12 12 12 0 0 0 12-12A12 12 0 0 0 12 0a12 12 0 0 0-.056 0zm4.962 7.224c.1-.002.321.023.465.14a.506.506 0 0 1 .171.325c.016.093.036.306.02.472-.18 1.898-.962 6.502-1.36 8.627-.168.9-.499 1.201-.82 1.23-.696.065-1.225-.46-1.9-.902-1.056-.693-1.653-1.124-2.678-1.8-1.185-.78-.417-1.21.258-1.91.177-.184 3.247-2.977 3.307-3.23.007-.032.014-.15-.056-.212s-.174-.041-.249-.024c-.106.024-1.793 1.14-5.061 3.345-.48.33-.913.49-1.302.48-.428-.008-1.252-.241-1.865-.44-.752-.245-1.349-.374-1.297-.789.027-.216.325-.437.893-.663 3.498-1.524 5.83-2.529 6.998-3.014 3.332-1.386 4.025-1.627 4.476-1.635z" />
                                    </svg>
                                </div>
                                <span class="text-[13px]" style="color: var(--text-primary);">تيليجرام</span>
                            </a>

                            <button type="button" onclick="copyProductLink(this)"
                                data-url="{{ route('products.show', $product) }}"
                                class="w-full flex items-center gap-3 px-4 py-2.5 hover:bg-gray-50 transition-colors border-t"
                                style="border-color: var(--border-light);">
                                <div class="w-7 h-7 rounded-lg flex items-center justify-center"
                                    style="background-color: var(--gold-soft);">
                                    <svg class="w-3.5 h-3.5" style="color: var(--gold);" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                    </svg>
                                </div>
                                <span class="text-[13px] copy-text" style="color: var(--text-primary);">نسخ الرابط</span>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="flex items-center gap-2 mb-6">
                    @php $avgRating = $product->reviews->avg('rating') ?? 0; @endphp
                    <div class="flex gap-0.5" style="color: var(--gold);">
                        @for($i = 1; $i <= 5; $i++)
                            <span class="text-[14px]">{{ $i <= $avgRating ? '★' : '☆' }}</span>
                        @endfor
                    </div>
                    <span class="text-[12px]" style="color: var(--text-tertiary);">({{ $product->reviews->count() }}
                        مراجعة)</span>
                </div>

                @php
                    $totalStock = $product->variants->sum('stock_quantity');
                @endphp

                @if($totalStock > 0 && $totalStock <= 5)
                    <div class="rounded-xl p-3.5 mb-5 flex items-center gap-3 border"
                        style="background-color: #fef2f2; border-color: #fecaca;">
                        <div class="w-9 h-9 rounded-lg flex items-center justify-center flex-shrink-0"
                            style="background-color: #dc2626;">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-[13px] font-semibold" style="color: #991b1b;">سارع بالشراء</p>
                            <p class="text-[12px]" style="color: #b91c1c;">
                                بقي <strong>{{ $totalStock }}</strong>
                                {{ $totalStock == 1 ? 'قطعة واحدة' : 'قطع' }} في المخزون
                            </p>
                        </div>
                    </div>
                @endif

                <div class="flex items-baseline gap-3 mb-5">
                    @if($product->discount_price)
                        <span class="text-3xl font-bold"
                            style="color: var(--gold);">${{ number_format($product->discount_price, 2) }}</span>
                        <span class="text-lg line-through"
                            style="color: var(--text-tertiary);">${{ number_format($product->price, 2) }}</span>
                        <span class="text-[11px] font-bold px-2 py-0.5 rounded"
                            style="background-color: #fee2e2; color: #dc2626;">
                            {{ round((($product->price - $product->discount_price) / $product->price) * 100) }}-%
                        </span>
                    @else
                        <span class="text-3xl font-bold"
                            style="color: var(--gold);">${{ number_format($product->price, 2) }}</span>
                    @endif
                </div>

                @if($product->description)
                    <p class="text-[14px] leading-relaxed mb-6" style="color: var(--text-secondary);">
                        {{ $product->description }}</p>
                @endif

                @if(session('success'))
                    <div class="px-4 py-3 rounded-lg mb-4 text-[13px]"
                        style="background-color: #f0fdf4; color: #166534; border: 1px solid #bbf7d0;">
                        {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="px-4 py-3 rounded-lg mb-4 text-[13px]"
                        style="background-color: #fef2f2; color: #991b1b; border: 1px solid #fecaca;">
                        {{ session('error') }}
                    </div>
                @endif

                @if($product->sizeGuide && $product->sizeGuide->is_active)
                    <button type="button" onclick="openSizeGuide()"
                        class="inline-flex items-center gap-2 text-[12px] font-semibold mb-4 transition-colors duration-150"
                        style="color: var(--gold);">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                        </svg>
                        دليل المقاسات
                    </button>
                @endif

                @if($product->variants->count())
                    <form action="{{ route('cart.add') }}" method="POST" class="mb-6" id="addToCartForm">
                        @csrf

                        @if(count($uniqueColorsData) > 1 || (count($uniqueColorsData) === 1 && $uniqueColorsData[0]['color'] !== 'بدون لون'))
                            <div class="mb-5">
                                <label class="block text-[12px] font-semibold mb-3" style="color: var(--text-primary);">
                                    اللون:
                                    <span id="selectedColorName"
                                        style="color: var(--gold); font-weight: 400;">{{ $uniqueColorsData[0]['color'] ?? '' }}</span>
                                </label>
                                <div class="flex flex-wrap gap-2" id="colorOptions">
                                    @foreach($uniqueColorsData as $index => $colorData)
                                        <button type="button" onclick="selectColor({{ $index }})" data-color-index="{{ $index }}"
                                            data-color="{{ $colorData['color'] }}" title="{{ $colorData['color'] }}" class="color-circle w-10 h-10 rounded-full border-2 transition-all duration-150 relative
                                                                       {{ $index === 0 ? 'scale-110' : '' }}"
                                            style="background-color: {{ $colorData['hex'] }}; border-color: {{ $index === 0 ? 'var(--gold)' : 'var(--border-medium)' }};">
                                            @if(in_array(strtolower($colorData['hex']), ['#ffffff', '#f5f5dc', '#c0c0c0']))
                                                <span class="absolute inset-0 rounded-full border"
                                                    style="border-color: var(--border-medium);"></span>
                                            @endif
                                        </button>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <div class="mb-5">
                            <label class="block text-[12px] font-semibold mb-3" style="color: var(--text-primary);">
                                المقاس:
                                <span id="selectedSizeName" style="color: var(--gold); font-weight: 400;">—</span>
                            </label>
                            <div class="flex flex-wrap gap-2" id="sizeOptions"></div>
                        </div>

                        <input type="hidden" name="variant_id" id="selectedVariantId" required>

                        <div id="variantError" class="hidden px-4 py-2 rounded-lg mb-4 text-[12px]"
                            style="background-color: #fef2f2; color: #991b1b; border: 1px solid #fecaca;">
                            الرجاء اختيار اللون والمقاس أولاً
                        </div>

                        <div class="flex gap-2 mt-5">
                            <div class="flex items-center border rounded-lg h-11"
                                style="border-color: var(--border-light); background-color: var(--bg-primary);">
                                <button type="button" onclick="decrementQty()" class="px-3 h-full font-bold transition-colors"
                                    style="color: var(--text-secondary);">−</button>
                                <input type="number" name="quantity" id="qtyInput" value="1" min="1"
                                    class="w-12 text-center border-0 focus:outline-none font-semibold text-[13px] bg-transparent"
                                    style="color: var(--text-primary);">
                                <button type="button" onclick="incrementQty()" class="px-3 h-full font-bold transition-colors"
                                    style="color: var(--text-secondary);">+</button>
                            </div>

                            <button type="submit"
                                class="flex-1 h-11 rounded-lg font-semibold text-[13px] text-white transition-all duration-200 hover:opacity-90"
                                style="background-color: var(--gold);">
                                أضف إلى السلة
                            </button>
                        </div>
                    </form>

                    <script>
                        const productVariants = @json($variantsData);
                        const uniqueColors = @json($uniqueColorNames);
                        const imagesByColor = @json($imagesByColor);

                        let selectedColorIndex = 0;
                        let selectedSize = null;
                        let currentImageIndex = 0;
                        let currentColorImages = [];

                        function renderImagesForColor(colorName) {
                            currentColorImages = imagesByColor[colorName] || [];

                            const mainImage = document.getElementById('mainImage');
                            const dotsContainer = document.getElementById('productImageDots');
                            const prevBtn = document.getElementById('prevImageBtn');
                            const nextBtn = document.getElementById('nextImageBtn');

                            if (!mainImage || !dotsContainer) return;

                            currentImageIndex = 0;

                            if (currentColorImages.length === 0) {
                                mainImage.src = '';
                                mainImage.style.display = 'none';
                                dotsContainer.innerHTML = '';
                                if (prevBtn) prevBtn.style.display = 'none';
                                if (nextBtn) nextBtn.style.display = 'none';
                                return;
                            }

                            mainImage.style.display = 'block';
                            mainImage.src = currentColorImages[0].path;

                            if (currentColorImages.length > 1) {
                                if (prevBtn) prevBtn.style.display = 'flex';
                                if (nextBtn) nextBtn.style.display = 'flex';
                            } else {
                                if (prevBtn) prevBtn.style.display = 'none';
                                if (nextBtn) nextBtn.style.display = 'none';
                            }

                            dotsContainer.innerHTML = '';

                            if (currentColorImages.length > 1) {
                                currentColorImages.forEach((img, i) => {
                                    const dot = document.createElement('button');
                                    dot.type = 'button';
                                    dot.className = 'product-image-dot';
                                    dot.style.cssText = 'width: 24px; height: 3px; border-radius: 2px; transition: all 0.3s; background-color: ' + (i === 0 ? '#ffffff' : 'rgba(255,255,255,0.4)') + ';';
                                    dot.onclick = () => goToProductImage(i);
                                    dotsContainer.appendChild(dot);
                                });
                            }
                        }

                        function goToProductImage(index) {
                            if (index < 0) index = currentColorImages.length - 1;
                            if (index >= currentColorImages.length) index = 0;

                            currentImageIndex = index;
                            const mainImage = document.getElementById('mainImage');

                            mainImage.style.opacity = '0';
                            setTimeout(() => {
                                mainImage.src = currentColorImages[index].path;
                                mainImage.style.opacity = '1';
                            }, 150);

                            updateProductDots();
                        }

                        function updateProductDots() {
                            const dots = document.querySelectorAll('.product-image-dot');
                            dots.forEach((dot, i) => {
                                if (i === currentImageIndex) {
                                    dot.style.backgroundColor = '#ffffff';
                                    dot.style.width = '32px';
                                } else {
                                    dot.style.backgroundColor = 'rgba(255,255,255,0.4)';
                                    dot.style.width = '24px';
                                }
                            });
                        }

                        function nextProductImage() {
                            goToProductImage(currentImageIndex + 1);
                        }

                        function prevProductImage() {
                            goToProductImage(currentImageIndex - 1);
                        }

                        function selectColor(index) {
                            selectedColorIndex = index;
                            selectedSize = null;

                            document.querySelectorAll('.color-circle').forEach((circle, i) => {
                                if (i === index) {
                                    circle.classList.add('scale-110');
                                    circle.style.borderColor = 'var(--gold)';
                                } else {
                                    circle.classList.remove('scale-110');
                                    circle.style.borderColor = 'var(--border-medium)';
                                }
                            });

                            document.getElementById('selectedColorName').textContent = uniqueColors[index];
                            renderImagesForColor(uniqueColors[index]);
                            buildSizeOptions();
                        }

                        function buildSizeOptions() {
                            const currentColor = uniqueColors[selectedColorIndex];
                            const sizesContainer = document.getElementById('sizeOptions');
                            sizesContainer.innerHTML = '';

                            const colorVariants = productVariants.filter(v => v.color === currentColor);

                            if (colorVariants.length === 0) {
                                sizesContainer.innerHTML = '<p class="text-[12px]" style="color: var(--text-tertiary);">لا توجد مقاسات لهذا اللون</p>';
                                return;
                            }

                            colorVariants.forEach(variant => {
                                const btn = document.createElement('button');
                                btn.type = 'button';
                                btn.dataset.variantId = variant.id;
                                btn.dataset.size = variant.size;
                                btn.disabled = variant.stock <= 0;

                                const baseStyle = 'px-4 py-1.5 rounded-lg border text-[12px] font-medium transition-all duration-150';

                                if (variant.stock <= 0) {
                                    btn.className = baseStyle;
                                    btn.style.cssText = 'border-color: var(--border-light); color: var(--text-tertiary); cursor: not-allowed; opacity: 0.5;';
                                    btn.textContent = variant.size + ' (نفذ)';
                                } else {
                                    btn.className = baseStyle;
                                    btn.style.cssText = 'border-color: var(--border-light); color: var(--text-primary); background-color: var(--bg-primary); cursor: pointer;';
                                    btn.onmouseover = function () { this.style.borderColor = 'var(--gold)'; };
                                    btn.onmouseout = function () { if (!this.classList.contains('selected')) this.style.borderColor = 'var(--border-light)'; };
                                    btn.onclick = () => selectSize(btn, variant);
                                    btn.textContent = variant.size;
                                }

                                sizesContainer.appendChild(btn);
                            });

                            if (colorVariants.length === 1 && colorVariants[0].stock > 0) {
                                const firstBtn = sizesContainer.querySelector('button');
                                if (firstBtn) firstBtn.click();
                            }
                        }

                        function selectSize(btn, variant) {
                            selectedSize = variant.size;

                            document.querySelectorAll('#sizeOptions button').forEach(b => {
                                b.style.borderColor = 'var(--border-light)';
                                b.style.backgroundColor = 'var(--bg-primary)';
                                b.style.color = 'var(--text-primary)';
                                b.classList.remove('selected');
                            });

                            btn.style.borderColor = 'var(--gold)';
                            btn.style.backgroundColor = 'var(--gold-soft)';
                            btn.style.color = 'var(--gold)';
                            btn.classList.add('selected');

                            document.getElementById('selectedSizeName').textContent = variant.size;
                            document.getElementById('selectedVariantId').value = variant.id;
                            document.getElementById('variantError').classList.add('hidden');
                        }

                        document.addEventListener('DOMContentLoaded', function () {
                            if (uniqueColors.length > 0) {
                                renderImagesForColor(uniqueColors[0]);
                            }
                            buildSizeOptions();
                        });

                        document.getElementById('addToCartForm').addEventListener('submit', function (e) {
                            const variantId = document.getElementById('selectedVariantId').value;
                            if (!variantId) {
                                e.preventDefault();
                                document.getElementById('variantError').classList.remove('hidden');
                                document.getElementById('variantError').scrollIntoView({ behavior: 'smooth', block: 'center' });
                            }
                        });

                        function incrementQty() {
                            const input = document.getElementById('qtyInput');
                            input.value = parseInt(input.value) + 1;
                        }
                        function decrementQty() {
                            const input = document.getElementById('qtyInput');
                            if (parseInt(input.value) > 1) {
                                input.value = parseInt(input.value) - 1;
                            }
                        }
                    </script>
                @else
                    <div class="px-4 py-3 rounded-lg mb-4 text-[13px]"
                        style="background-color: #fffbeb; color: #92400e; border: 1px solid #fde68a;">
                        هذا المنتج غير متوفر حالياً بمتغيرات.
                    </div>
                @endif

                <div class="grid grid-cols-3 gap-3 pt-5 border-t" style="border-color: var(--border-light);">
                    <div class="flex flex-col items-center text-center gap-2 py-2">
                        <svg class="w-5 h-5" style="color: var(--gold);" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0" />
                        </svg>
                        <p class="text-[11px]" style="color: var(--text-secondary);">شحن سريع</p>
                    </div>
                    <div class="flex flex-col items-center text-center gap-2 py-2">
                        <svg class="w-5 h-5" style="color: var(--gold);" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                        </svg>
                        <p class="text-[11px]" style="color: var(--text-secondary);">دفع آمن</p>
                    </div>
                    <div class="flex flex-col items-center text-center gap-2 py-2">
                        <svg class="w-5 h-5" style="color: var(--gold);" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                        <p class="text-[11px]" style="color: var(--text-secondary);">إرجاع مجاني</p>
                    </div>
                </div>
            </div>
        </div>

        <section class="mb-14" id="reviews-section">
            <div class="mb-6">
                <h2 class="text-xl font-bold mb-1" style="color: var(--text-primary);">آراء العملاء</h2>
                <p class="text-[13px]" style="color: var(--text-secondary);">({{ $product->reviews->count() }} مراجعة)</p>
            </div>

            <div class="grid md:grid-cols-3 gap-5">

                <div class="md:col-span-1">
                    <div class="rounded-xl border p-5"
                        style="background-color: var(--bg-primary); border-color: var(--border-light);">
                        @php
                            $avg = $product->reviews->avg('rating') ?? 0;
                            $total = $product->reviews->count();
                        @endphp

                        <div class="text-center mb-5">
                            <div class="text-4xl font-bold mb-2" style="color: var(--gold);">{{ number_format($avg, 1) }}
                            </div>
                            <div class="flex justify-center gap-0.5 mb-2" style="color: var(--gold);">
                                @for($i = 1; $i <= 5; $i++)
                                    <span>{{ $i <= round($avg) ? '★' : '☆' }}</span>
                                @endfor
                            </div>
                            <p class="text-[12px]" style="color: var(--text-secondary);">بناءً على {{ $total }} تقييم</p>
                        </div>

                        <div class="space-y-2">
                            @for($i = 5; $i >= 1; $i--)
                                @php
                                    $count = $product->reviews->where('rating', $i)->count();
                                    $percent = $total > 0 ? ($count / $total) * 100 : 0;
                                @endphp
                                <div class="flex items-center gap-2 text-[12px]">
                                    <span class="w-6" style="color: var(--text-secondary);">{{ $i }}</span>
                                    <div class="flex-1 rounded-full h-1.5 overflow-hidden"
                                        style="background-color: var(--bg-tertiary);">
                                        <div class="h-1.5 rounded-full transition-all"
                                            style="width: {{ $percent }}%; background-color: var(--gold);"></div>
                                    </div>
                                    <span class="w-6 text-left" style="color: var(--text-tertiary);">{{ $count }}</span>
                                </div>
                            @endfor
                        </div>

                        @auth
                            @php
                                $userReviewed = \App\Models\Review::where('user_id', auth()->id())
                                    ->where('product_id', $product->id)->exists();
                            @endphp

                            @if(!$userReviewed)
                                <button type="button" onclick="showReviewForm()"
                                    class="w-full mt-5 h-10 rounded-lg font-semibold text-[12px] text-white transition-all duration-200 hover:opacity-90"
                                    style="background-color: var(--gold);">
                                    أضف تقييمك
                                </button>
                            @else
                                <div class="mt-5 px-3 py-2.5 rounded-lg text-[12px] text-center"
                                    style="background-color: #f0fdf4; color: #166534;">
                                    تم تقييم هذا المنتج
                                </div>
                            @endif
                        @else
                            <a href="{{ route('login') }}"
                                class="block w-full mt-5 h-10 rounded-lg font-semibold text-[12px] text-center leading-10 transition-all duration-200"
                                style="background-color: var(--bg-tertiary); color: var(--text-primary);">
                                سجّل دخولك للتقييم
                            </a>
                        @endauth
                    </div>
                </div>

                <div class="md:col-span-2">

                    @auth
                        @php
                            $userReviewed = \App\Models\Review::where('user_id', auth()->id())
                                ->where('product_id', $product->id)->exists();
                        @endphp
                        @if(!$userReviewed)
                            <div id="reviewForm" class="hidden rounded-xl border-2 p-5 mb-5"
                                style="background-color: var(--bg-primary); border-color: var(--gold);">
                                <h3 class="text-[15px] font-bold mb-4" style="color: var(--text-primary);">شاركنا رأيك</h3>

                                <form onsubmit="submitReview(event)" id="reviewFormElement">
                                    <input type="hidden" name="product_id" value="{{ $product->id }}">

                                    <div class="mb-4">
                                        <label class="block text-[12px] font-semibold mb-2"
                                            style="color: var(--text-primary);">تقييمك</label>
                                        <div class="flex gap-1 text-3xl" id="ratingStars">
                                            @for($i = 1; $i <= 5; $i++)
                                                <button type="button" onclick="setRating({{ $i }})" data-star="{{ $i }}"
                                                    class="rating-star transition-colors duration-150"
                                                    style="color: var(--border-medium);">★</button>
                                            @endfor
                                        </div>
                                        <input type="hidden" name="rating" id="ratingInput" value="0">
                                    </div>

                                    <div class="mb-4">
                                        <label class="block text-[12px] font-semibold mb-2" style="color: var(--text-primary);">
                                            تعليقك <span style="color: var(--text-tertiary); font-weight: 400;">(اختياري)</span>
                                        </label>
                                        <textarea name="comment" rows="3" placeholder="اكتب تجربتك مع هذا المنتج..."
                                            class="w-full rounded-lg py-2.5 px-3 text-[13px] focus:outline-none focus:ring-2 transition-all duration-150"
                                            style="background-color: var(--bg-tertiary); border: 1px solid var(--border-light); color: var(--text-primary); --tw-ring-color: var(--gold);"></textarea>
                                    </div>

                                    <div class="flex gap-2">
                                        <button type="submit"
                                            class="h-9 px-5 rounded-lg font-semibold text-[12px] text-white transition-all duration-200 hover:opacity-90"
                                            style="background-color: var(--gold);">
                                            إرسال
                                        </button>
                                        <button type="button" onclick="hideReviewForm()"
                                            class="h-9 px-5 rounded-lg font-semibold text-[12px] transition-all duration-150"
                                            style="background-color: var(--bg-tertiary); color: var(--text-primary);">
                                            إلغاء
                                        </button>
                                    </div>
                                </form>
                            </div>
                        @endif
                    @endauth

                    <div id="reviewsList" class="space-y-3">
                        @forelse($product->reviews as $review)
                            <div class="rounded-xl border p-5 review-item"
                                style="background-color: var(--bg-primary); border-color: var(--border-light);">
                                <div class="flex items-start gap-3 mb-3">
                                    <div class="w-10 h-10 rounded-lg flex items-center justify-center text-white font-semibold text-[13px] flex-shrink-0"
                                        style="background-color: var(--gold);">
                                        {{ mb_substr($review->user->name ?? 'U', 0, 1, 'UTF-8') }}
                                    </div>
                                    <div class="flex-1">
                                        <div class="flex items-center justify-between gap-3 flex-wrap">
                                            <div>
                                                <p class="text-[13px] font-semibold" style="color: var(--text-primary);">
                                                    {{ $review->user->name ?? 'مستخدم' }}</p>
                                                <p class="text-[11px]" style="color: var(--text-tertiary);">
                                                    {{ $review->created_at->format('Y-m-d') }}</p>
                                            </div>
                                            <div class="flex gap-0.5" style="color: var(--gold);">
                                                @for($i = 1; $i <= 5; $i++)
                                                    <span class="text-[13px]">{{ $i <= $review->rating ? '★' : '☆' }}</span>
                                                @endfor
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @if($review->comment)
                                    <p class="text-[13px] leading-relaxed" style="color: var(--text-secondary);">
                                        {{ $review->comment }}</p>
                                @endif
                            </div>
                        @empty
                            <div class="rounded-xl border p-10 text-center"
                                style="background-color: var(--bg-primary); border-color: var(--border-light);">
                                <div class="w-12 h-12 rounded-lg flex items-center justify-center mx-auto mb-3"
                                    style="background-color: var(--bg-tertiary);">
                                    <svg class="w-6 h-6" style="color: var(--text-tertiary);" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                            d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                                    </svg>
                                </div>
                                <p class="text-[13px]" style="color: var(--text-secondary);">لا توجد تقييمات بعد. كن أول من
                                    يقيّم هذا المنتج</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </section>

        @if($relatedProducts->count())
            <section class="mb-14">
                <div class="flex items-end justify-between mb-6">
                    <h2 class="text-xl font-bold" style="color: var(--text-primary);">منتجات مشابهة</h2>
                </div>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-5">
                    @foreach($relatedProducts as $relatedProduct)
                        @include('layouts.partials.product-card', ['product' => $relatedProduct])
                    @endforeach
                </div>
            </section>
        @endif

        @if(isset($recentlyViewed) && $recentlyViewed->count())
            <section>
                <div class="flex items-end justify-between mb-6">
                    <h2 class="text-xl font-bold" style="color: var(--text-primary);">تصفّحت مؤخراً</h2>
                    <a href="{{ route('products.recentlyViewed') }}" class="text-[13px] font-semibold flex items-center gap-1"
                        style="color: var(--gold);">
                        عرض الكل
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                    </a>
                </div>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-5">
                    @foreach($recentlyViewed as $recentProduct)
                        @include('layouts.partials.product-card', ['product' => $recentProduct])
                    @endforeach
                </div>
            </section>
        @endif

    </div>

    <div id="imageFullscreenModal" class="hidden fixed inset-0 z-[9999] flex items-center justify-center p-4"
        style="background-color: rgba(0, 0, 0, 0.95); backdrop-filter: blur(12px);"
        onclick="if(event.target === this) closeFullscreen()">

        <button type="button" onclick="closeFullscreen()" title="إغلاق"
            class="absolute top-4 right-4 z-10 w-11 h-11 rounded-lg flex items-center justify-center text-white transition-all duration-200"
            style="background-color: rgba(255, 255, 255, 0.1);"
            onmouseover="this.style.backgroundColor='rgba(255, 255, 255, 0.25)';"
            onmouseout="this.style.backgroundColor='rgba(255, 255, 255, 0.1)';">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>

        <img id="fullscreenImage" src="" alt="" class="max-w-full max-h-full object-contain rounded-lg"
            style="direction: ltr;" onclick="event.stopPropagation()">

        <p class="absolute bottom-6 left-1/2 -translate-x-1/2 text-white text-[12px] opacity-60">
            اضغط ESC أو انقر خارج الصورة للإغلاق
        </p>
    </div>

    <style>
        #imageFullscreenModal {
            opacity: 0;
            transition: opacity 0.15s ease;
        }

        #imageFullscreenModal:not(.hidden) {
            opacity: 1;
        }

        #fullscreenImage {
            animation: zoomIn 0.2s ease;
        }

        @keyframes zoomIn {
            from {
                transform: scale(0.9);
                opacity: 0;
            }

            to {
                transform: scale(1);
                opacity: 1;
            }
        }

        #mainImage {
            transition: opacity 0.15s ease;
        }
    </style>

    @if($product->sizeGuide && $product->sizeGuide->is_active)
        <div id="sizeGuideModal" class="hidden fixed inset-0 bg-black/60 z-50 flex items-center justify-center p-4"
            onclick="if(event.target === this) closeSizeGuide()">
            <div class="rounded-xl max-w-2xl w-full max-h-[90vh] overflow-y-auto shadow-2xl"
                style="background-color: var(--bg-primary);" onclick="event.stopPropagation()">

                <div class="sticky top-0 p-5 flex items-center justify-between border-b rounded-t-xl"
                    style="background-color: var(--bg-primary); border-color: var(--border-light);">
                    <div>
                        <h3 class="text-lg font-bold" style="color: var(--text-primary);">{{ $product->sizeGuide->name }}</h3>
                        @if($product->sizeGuide->description)
                            <p class="text-[12px] mt-1" style="color: var(--text-secondary);">{{ $product->sizeGuide->description }}
                            </p>
                        @endif
                    </div>
                    <button onclick="closeSizeGuide()"
                        class="w-9 h-9 rounded-lg flex items-center justify-center transition-all duration-150 flex-shrink-0"
                        style="background-color: var(--bg-tertiary); color: var(--text-secondary);"
                        onmouseover="this.style.backgroundColor='#dc2626'; this.style.color='white';"
                        onmouseout="this.style.backgroundColor='var(--bg-tertiary)'; this.style.color='var(--text-secondary)';">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <div class="p-5">
                    @if($product->sizeGuide->items->count())
                        <div class="overflow-x-auto rounded-lg border" style="border-color: var(--border-light);">
                            <table class="w-full text-[13px]">
                                <thead>
                                    <tr style="background-color: var(--gold-soft);">
                                        <th class="p-3 text-right font-semibold" style="color: var(--text-primary);">المقاس</th>
                                        <th class="p-3 text-center font-semibold" style="color: var(--text-primary);">الصدر</th>
                                        <th class="p-3 text-center font-semibold" style="color: var(--text-primary);">الخصر</th>
                                        <th class="p-3 text-center font-semibold" style="color: var(--text-primary);">الأرداف</th>
                                        <th class="p-3 text-center font-semibold" style="color: var(--text-primary);">الطول</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($product->sizeGuide->items as $item)
                                        <tr class="border-t" style="border-color: var(--border-light);">
                                            <td class="p-3 text-right">
                                                <span class="text-white px-2.5 py-1 rounded text-[11px] font-bold"
                                                    style="background-color: var(--gold);">
                                                    {{ $item->size }}
                                                </span>
                                            </td>
                                            <td class="p-3 text-center" style="color: var(--text-secondary);">{{ $item->chest ?? '—' }}
                                            </td>
                                            <td class="p-3 text-center" style="color: var(--text-secondary);">{{ $item->waist ?? '—' }}
                                            </td>
                                            <td class="p-3 text-center" style="color: var(--text-secondary);">{{ $item->hips ?? '—' }}
                                            </td>
                                            <td class="p-3 text-center" style="color: var(--text-secondary);">{{ $item->length ?? '—' }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-5 rounded-lg p-3.5 text-[12px]"
                            style="background-color: var(--gold-soft); color: var(--text-secondary);">
                            قِس نفسك بشريط قياس ناعم، وضع الشريط بشكل مريح على الجسم دون شد زائد.
                        </div>
                    @else
                        <p class="text-center py-8 text-[13px]" style="color: var(--text-tertiary);">لا توجد تفاصيل مقاسات لهذا
                            الدليل.</p>
                    @endif
                </div>
            </div>
        </div>
    @endif

    <script>
        function showReviewForm() {
            const form = document.getElementById('reviewForm');
            if (form) {
                form.classList.remove('hidden');
                form.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
        }

        function hideReviewForm() {
            const form = document.getElementById('reviewForm');
            if (form) form.classList.add('hidden');
            const input = document.getElementById('ratingInput');
            if (input) input.value = 0;
            document.querySelectorAll('.rating-star').forEach(s => {
                s.style.color = 'var(--border-medium)';
            });
        }

        function setRating(value) {
            document.getElementById('ratingInput').value = value;
            document.querySelectorAll('.rating-star').forEach(star => {
                if (parseInt(star.dataset.star) <= value) {
                    star.style.color = 'var(--gold)';
                } else {
                    star.style.color = 'var(--border-medium)';
                }
            });
        }

        async function submitReview(event) {
            event.preventDefault();
            const form = event.target;
            const formData = new FormData(form);
            const rating = formData.get('rating');

            if (rating == 0) {
                showToast('الرجاء اختيار التقييم بالنجوم', 'error');
                return;
            }

            const data = {
                product_id: formData.get('product_id'),
                rating: parseInt(rating),
                comment: formData.get('comment'),
            };

            try {
                const response = await fetch('{{ route('reviews.store') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify(data),
                });

                const result = await response.json();

                if (!response.ok) {
                    showToast(result.message || 'حدث خطأ', 'error');
                    return;
                }

                const reviewsList = document.getElementById('reviewsList');
                const emptyState = reviewsList.querySelector('.text-center');
                if (emptyState) emptyState.closest('.rounded-xl').remove();

                const reviewHtml = `
                        <div class="rounded-xl border p-5 review-item" style="background-color: var(--bg-primary); border-color: var(--border-light);">
                            <div class="flex items-start gap-3 mb-3">
                                <div class="w-10 h-10 rounded-lg flex items-center justify-center text-white font-semibold text-[13px] flex-shrink-0" style="background-color: var(--gold);">
                                    ${result.review.user_initial}
                                </div>
                                <div class="flex-1">
                                    <div class="flex items-center justify-between gap-3 flex-wrap">
                                        <div>
                                            <p class="text-[13px] font-semibold" style="color: var(--text-primary);">${result.review.user_name}</p>
                                            <p class="text-[11px]" style="color: var(--text-tertiary);">${result.review.created_at}</p>
                                        </div>
                                        <div class="flex gap-0.5" style="color: var(--gold);">
                                            ${'★'.repeat(result.review.rating)}${'☆'.repeat(5 - result.review.rating)}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            ${result.review.comment ? `<p class="text-[13px] leading-relaxed" style="color: var(--text-secondary);">${result.review.comment}</p>` : ''}
                        </div>
                    `;
                reviewsList.insertAdjacentHTML('afterbegin', reviewHtml);

                const formDiv = document.getElementById('reviewForm');
                if (formDiv) formDiv.remove();

                showToast(result.message, 'success');
            } catch (error) {
                console.error(error);
                showToast('حدث خطأ، حاول مرة أخرى', 'error');
            }
        }

        function openSizeGuide() {
            const modal = document.getElementById('sizeGuideModal');
            if (modal) {
                modal.classList.remove('hidden');
                document.body.style.overflow = 'hidden';
            }
        }

        function closeSizeGuide() {
            const modal = document.getElementById('sizeGuideModal');
            if (modal) {
                modal.classList.add('hidden');
                document.body.style.overflow = '';
            }
        }

        function openFullscreen(imageSrc) {
            const modal = document.getElementById('imageFullscreenModal');
            const img = document.getElementById('fullscreenImage');

            img.src = imageSrc;
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';

            requestAnimationFrame(() => {
                modal.style.opacity = '1';
            });
        }

        function closeFullscreen() {
            const modal = document.getElementById('imageFullscreenModal');

            modal.style.opacity = '0';
            document.body.style.overflow = '';

            setTimeout(() => {
                modal.classList.add('hidden');
                document.getElementById('fullscreenImage').src = '';
            }, 150);
        }

        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') {
                closeSizeGuide();
                closeFullscreen();
            }
            if (e.key === 'ArrowRight') {
                if (typeof prevProductImage === 'function') prevProductImage();
            }
            if (e.key === 'ArrowLeft') {
                if (typeof nextProductImage === 'function') nextProductImage();
            }
        });
    </script>

@endsection