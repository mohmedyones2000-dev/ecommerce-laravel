@extends('layouts.app')

@section('title', $product->name . ' | متجري')

@section('content')

    <style>
        /* CSS Bridge — يحافظ على الـ JS القديم بدون تعديل */
        .product-detail {
            --gold: #14b8a6;
            --gold-soft: rgba(20, 184, 166, 0.1);
            --border-light: #e6e6e4;
            --border-medium: #c8c8c6;
            --text-primary: #0f0f0f;
            --text-secondary: #6b6b5e;
            --text-tertiary: #9a9a8c;
            --bg-primary: #fafaf9;
            --bg-tertiary: #f5f5f4;
        }

        .dark .product-detail {
            --gold: #2dd4bf;
            --gold-soft: rgba(45, 212, 191, 0.15);
            --border-light: #262626;
            --border-medium: #3c3c3c;
            --text-primary: #f5f5f0;
            --text-secondary: #969690;
            --text-tertiary: #6e6e64;
            --bg-primary: #0a0a0a;
            --bg-tertiary: #141414;
        }

        #mainImage {
            transition: opacity 0.15s ease;
        }

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
    </style>

    <div class="product-detail container-x pt-12 lg:pt-16 pb-24">

        {{-- ════════════ BREADCRUMB ════════════ --}}
        <nav class="flex items-center gap-3 text-[10px] font-medium uppercase tracking-[0.2em] text-ink-faint dark:text-cream/40 mb-12 flex-wrap"
            aria-label="breadcrumb">
            <a href="{{ route('home') }}" class="hover:text-forest dark:hover:text-gold transition-colors">الرئيسية</a>
            <span class="opacity-40">/</span>
            <a href="{{ route('products.index') }}"
                class="hover:text-forest dark:hover:text-gold transition-colors">المتجر</a>
            @if($product->category)
                <span class="opacity-40">/</span>
                <a href="{{ route('products.index', ['category' => $product->category->id]) }}"
                    class="hover:text-forest dark:hover:text-gold transition-colors">
                    {{ $product->category->name }}
                </a>
            @endif
            <span class="opacity-40">/</span>
            <span class="text-ink dark:text-cream">{{ $product->name }}</span>
        </nav>

        {{-- ════════════ MAIN GRID ════════════ --}}
        <div class="grid lg:grid-cols-12 gap-10 lg:gap-16 mb-24">

            {{-- ─── GALLERY ─── --}}
            <div class="lg:col-span-7">
                @php
                    $firstColor = $product->variants->pluck('color')->filter()->first();
                    $firstColorKey = $firstColor ?: 'بدون لون';
                    $initialImages = $imagesByColor[$firstColorKey] ?? [];
                    if (empty($initialImages)) {
                        $initialImages = collect($imagesByColor)->flatten(1)->take(7)->values()->toArray();
                    }
                @endphp

                <div class="relative group product-gallery">
                    <div class="aspect-square overflow-hidden bg-cream-warm dark:bg-zinc-900" style="border-radius: 4px;">
                        @if(!empty($initialImages))
                            <img id="mainImage" src="{{ $initialImages[0]['path'] }}" alt="{{ $product->name }}"
                                class="w-full h-full object-cover">

                            {{-- Fullscreen button --}}
                            <button type="button" onclick="openFullscreen(document.getElementById('mainImage').src)"
                                title="عرض الصورة كاملة"
                                class="absolute top-4 left-4 z-30 w-10 h-10 flex items-center justify-center bg-ink/70 hover:bg-ink text-cream backdrop-blur-sm opacity-0 group-hover:opacity-100 transition-all duration-300"
                                style="border-radius: 4px;">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4" />
                                </svg>
                            </button>

                            {{-- Nav arrows --}}
                            <button type="button" onclick="prevProductImage()" id="prevImageBtn" title="السابق"
                                class="absolute top-1/2 -translate-y-1/2 right-4 z-20 w-11 h-11 flex items-center justify-center bg-ink/60 hover:bg-ink text-cream backdrop-blur-sm opacity-0 group-hover:opacity-100 transition-all duration-300"
                                style="border-radius: 4px;">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                                </svg>
                            </button>
                            <button type="button" onclick="nextProductImage()" id="nextImageBtn" title="التالي"
                                class="absolute top-1/2 -translate-y-1/2 left-4 z-20 w-11 h-11 flex items-center justify-center bg-ink/60 hover:bg-ink text-cream backdrop-blur-sm opacity-0 group-hover:opacity-100 transition-all duration-300"
                                style="border-radius: 4px;">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                                </svg>
                            </button>

                            <div id="productImageDots" class="absolute bottom-4 left-1/2 -translate-x-1/2 flex gap-1.5 z-20">
                            </div>
                        @else
                            <div class="w-full h-full flex items-center justify-center text-ink-faint dark:text-cream/30">
                                <svg class="w-20 h-20" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                </svg>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- ─── INFO ─── --}}
            <div class="lg:col-span-5">
                {{-- Brand --}}
                @if($product->brand)
                    <span class="eyebrow block mb-4">— {{ $product->brand->name }}</span>
                @endif

                {{-- Title --}}
                <h1 class="font-display text-3xl md:text-4xl lg:text-5xl font-bold leading-[1.05] tracking-tight mb-6">
                    {{ $product->name }}
                </h1>

                {{-- Rating --}}
                @php
                    $avgRating = $product->reviews->avg('rating') ?? 0;
                    $reviewCount = $product->reviews->count();
                @endphp
                <div class="flex items-center gap-3 mb-6 text-sm">
                    <div class="flex gap-0.5 text-gold">
                        @for($i = 1; $i <= 5; $i++)
                            <span>{{ $i <= $avgRating ? '★' : '☆' }}</span>
                        @endfor
                    </div>
                    <span class="text-ink-muted dark:text-cream/50">
                        {{ number_format($avgRating, 1) }} · {{ $reviewCount }}
                        {{ $reviewCount === 1 ? 'مراجعة' : 'مراجعة' }}
                    </span>
                </div>

                {{-- Price --}}
                <div class="flex items-baseline gap-4 mb-8 pb-8 border-b border-stone-200 dark:border-stone-800">
                    @if($product->discount_price)
                        <span class="font-display text-4xl font-bold text-forest dark:text-gold">
                            ${{ number_format($product->discount_price, 2) }}
                        </span>
                        <span class="text-lg line-through text-ink-faint dark:text-cream/40">
                            ${{ number_format($product->price, 2) }}
                        </span>
                        <span class="text-[10px] font-bold tracking-widest uppercase px-2.5 py-1 bg-gold text-ink"
                            style="border-radius: 2px;">
                            −{{ round((($product->price - $product->discount_price) / $product->price) * 100) }}%
                        </span>
                    @else
                        <span class="font-display text-4xl font-bold">
                            ${{ number_format($product->price, 2) }}
                        </span>
                    @endif
                </div>

                {{-- Low stock warning --}}
                @php $totalStock = $product->variants->sum('stock_quantity'); @endphp
                @if($totalStock > 0 && $totalStock <= 5)
                    <div class="flex items-center gap-3 mb-6 p-3 border border-red-200 dark:border-red-900/50 bg-red-50 dark:bg-red-950/20"
                        style="border-radius: 4px;">
                        <svg class="w-5 h-5 text-red-600 dark:text-red-400 shrink-0" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                        <p class="text-xs text-red-800 dark:text-red-300">
                            بقي <strong>{{ $totalStock }}</strong> {{ $totalStock == 1 ? 'قطعة واحدة' : 'قطع' }} في المخزون
                        </p>
                    </div>
                @endif

                {{-- Description --}}
                @if($product->description)
                    <p class="text-base leading-relaxed text-ink-muted dark:text-cream/70 mb-8 text-pretty">
                        {{ $product->description }}
                    </p>
                @endif

                {{-- Session messages --}}
                @if(session('success'))
                    <div class="px-4 py-3 mb-5 text-sm border border-green-200 dark:border-green-900/50 bg-green-50 dark:bg-green-950/20 text-green-800 dark:text-green-300"
                        style="border-radius: 4px;">
                        {{ session('success') }}
                    </div>
                @endif
                @if(session('error'))
                    <div class="px-4 py-3 mb-5 text-sm border border-red-200 dark:border-red-900/50 bg-red-50 dark:bg-red-950/20 text-red-800 dark:text-red-300"
                        style="border-radius: 4px;">
                        {{ session('error') }}
                    </div>
                @endif

                {{-- Size guide link --}}
                @if($product->sizeGuide && $product->sizeGuide->is_active)
                    <button type="button" onclick="openSizeGuide()"
                        class="inline-flex items-center gap-2 text-xs font-semibold mb-6 text-forest dark:text-gold hover:opacity-70 transition-opacity">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                        </svg>
                        دليل المقاسات
                    </button>
                @endif

                {{-- Wishlist & Share --}}
                <div class="flex flex-wrap gap-2 mb-8">
                    @auth
                                @php
                                    $inWishlist = \App\Models\Wishlist::where('user_id', auth()->id())
                                        ->where('product_id', $product->id)->exists();
                                @endphp
                                <button type="button" data-wishlist-btn data-product-id="{{ $product->id }}"
                                    data-in-wishlist="{{ $inWishlist ? '1' : '0' }}" onclick="toggleWishlist(this)" class="inline-flex items-center gap-2 h-10 px-4 text-xs font-semibold border transition-colors
                                                                   {{ $inWishlist
                        ? 'border-gold text-gold bg-gold/5'
                        : 'border-stone-300 dark:border-stone-700 hover:border-gold hover:text-gold' }}"
                                    style="border-radius: 4px;">
                                    <svg class="w-4 h-4 wishlist-icon" fill="{{ $inWishlist ? 'currentColor' : 'none' }}"
                                        stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                    </svg>
                                    <span class="wishlist-text">{{ $inWishlist ? 'في المفضلة' : 'أضف للمفضلة' }}</span>
                                </button>
                    @endauth

                    <div class="relative inline-block" x-data="{ shareOpen: false }">
                        <button type="button" @click="shareOpen = !shareOpen"
                            class="inline-flex items-center gap-2 h-10 px-4 text-xs font-semibold border border-stone-300 dark:border-stone-700 hover:border-forest dark:hover:border-gold transition-colors"
                            style="border-radius: 4px;">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z" />
                            </svg>
                            مشاركة
                        </button>

                        <div x-show="shareOpen" @click.away="shareOpen = false" x-cloak x-transition.opacity.duration.200ms
                            class="absolute top-full right-0 mt-2 w-56 bg-canvas dark:bg-zinc-900 border border-stone-200 dark:border-stone-800 shadow-2xl py-2 z-50"
                            style="border-radius: 4px;">
                            <p
                                class="px-4 py-2 text-[10px] font-semibold tracking-widest uppercase text-ink-faint dark:text-cream/40 border-b border-stone-200 dark:border-stone-800">
                                شارك عبر
                            </p>

                            @foreach([
                                    ['url' => 'https://www.facebook.com/sharer/sharer.php?u=' . urlencode(route('products.show', $product)), 'label' => 'فيسبوك', 'bg' => 'bg-blue-500', 'path' => 'M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z'],
                                    ['url' => 'https://wa.me/?text=' . urlencode($product->name . ' - ' . route('products.show', $product)), 'label' => 'واتساب', 'bg' => 'bg-green-500', 'path' => 'M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z'],
                                    ['url' => 'https://twitter.com/intent/tweet?text=' . urlencode($product->name) . '&url=' . urlencode(route('products.show', $product)), 'label' => 'تويتر', 'bg' => 'bg-zinc-900', 'path' => 'M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z'],
                                    ['url' => 'https://t.me/share/url?url=' . urlencode(route('products.show', $product)) . '&text=' . urlencode($product->name), 'label' => 'تيليجرام', 'bg' => 'bg-sky-500', 'path' => 'M11.944 0A12 12 0 0 0 0 12a12 12 0 0 0 12 12 12 12 0 0 0 12-12A12 12 0 0 0 12 0a12 12 0 0 0-.056 0zm4.962 7.224c.1-.002.321.023.465.14a.506.506 0 0 1 .171.325c.016.093.036.306.02.472-.18 1.898-.962 6.502-1.36 8.627-.168.9-.499 1.201-.82 1.23-.696.065-1.225-.46-1.9-.902-1.056-.693-1.653-1.124-2.678-1.8-1.185-.78-.417-1.21.258-1.91.177-.184 3.247-2.977 3.307-3.23.007-.032.014-.15-.056-.212s-.174-.041-.249-.024c-.106.024-1.793 1.14-5.061 3.345-.48.33-.913.49-1.302.48-.428-.008-1.252-.241-1.865-.44-.752-.245-1.349-.374-1.297-.789.027-.216.325-.437.893-.663 3.498-1.524 5.83-2.529 6.998-3.014 3.332-1.386 4.025-1.627 4.476-1.635z'],
                                ] as $share)
                                <a href="{{ $share['url'] }}" target="_blank" rel="noopener"
                                    class="flex items-center gap-3 px-4 py-2.5 hover:bg-stone-100 dark:hover:bg-zinc-800 transition-colors">
                                    <div class="w-7 h-7 {{ $share['bg'] }} flex items-center justify-center"
                                        style="border-radius: 4px;">
                                        <svg class="w-3.5 h-3.5 text-white" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="{{ $share['path'] }}" />
                                        </svg>
                                    </div>
                                    <span class="text-sm text-ink dark:text-cream">{{ $share['label'] }}</span>
                                </a>
                            @endforeach

                            <button type="button" onclick="copyProductLink(this)"
                                data-url="{{ route('products.show', $product) }}"
                                class="w-full flex items-center gap-3 px-4 py-2.5 hover:bg-stone-100 dark:hover:bg-zinc-800 transition-colors border-t border-stone-200 dark:border-stone-800">
                                <div class="w-7 h-7 bg-forest/10 flex items-center justify-center"
                                    style="border-radius: 4px;">
                                    <svg class="w-3.5 h-3.5 text-forest dark:text-gold" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24" stroke-width="1.8">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                    </svg>
                                </div>
                                <span class="text-sm copy-text text-ink dark:text-cream">نسخ الرابط</span>
                            </button>
                        </div>
                    </div>
                </div>

                {{-- ════════════ ADD TO CART FORM ════════════ --}}
                @if($product->variants->count())
                    <form action="{{ route('cart.add') }}" method="POST" id="addToCartForm" class="mb-10">
                        @csrf

                        {{-- Colors --}}
                        @if(count($uniqueColorsData) > 1 || (count($uniqueColorsData) === 1 && $uniqueColorsData[0]['color'] !== 'بدون لون'))
                            <div class="mb-6">
                                <label
                                    class="block text-[10px] font-semibold tracking-widest uppercase text-ink-muted dark:text-cream/50 mb-3">
                                    اللون —
                                    <span id="selectedColorName"
                                        class="text-ink dark:text-cream normal-case tracking-normal font-medium">{{ $uniqueColorsData[0]['color'] ?? '' }}</span>
                                </label>
                                <div class="flex flex-wrap gap-2.5" id="colorOptions">
                                    @foreach($uniqueColorsData as $index => $colorData)
                                        <button type="button" onclick="selectColor({{ $index }})" data-color-index="{{ $index }}"
                                            data-color="{{ $colorData['color'] }}" title="{{ $colorData['color'] }}"
                                            class="color-circle relative w-10 h-10 border-2 transition-all duration-200 {{ $index === 0 ? 'scale-110' : '' }}"
                                            style="background-color: {{ $colorData['hex'] }}; border-radius: 50%; border-color: {{ $index === 0 ? '#14b8a6' : 'var(--border-medium)' }};">
                                            @if(in_array(strtolower($colorData['hex']), ['#ffffff', '#f5f5dc', '#c0c0c0']))
                                                <span class="absolute inset-0 rounded-full border"
                                                    style="border-color: var(--border-medium);"></span>
                                            @endif
                                        </button>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        {{-- Sizes --}}
                        <div class="mb-6">
                            <label
                                class="block text-[10px] font-semibold tracking-widest uppercase text-ink-muted dark:text-cream/50 mb-3">
                                المقاس —
                                <span id="selectedSizeName"
                                    class="text-ink dark:text-cream normal-case tracking-normal font-medium">—</span>
                            </label>
                            <div class="flex flex-wrap gap-2" id="sizeOptions"></div>
                        </div>

                        <input type="hidden" name="variant_id" id="selectedVariantId" required>

                        <div id="variantError"
                            class="hidden px-4 py-3 text-xs border border-red-200 dark:border-red-900/50 bg-red-50 dark:bg-red-950/20 text-red-800 dark:text-red-300 mb-4"
                            style="border-radius: 4px;">
                            الرجاء اختيار اللون والمقاس أولاً
                        </div>

                        {{-- Quantity + Add to cart --}}
                        <div class="flex gap-3">
                            <div class="flex items-center border border-stone-300 dark:border-stone-700 bg-canvas"
                                style="border-radius: 4px;">
                                <button type="button" onclick="decrementQty()"
                                    class="w-11 h-12 text-ink dark:text-cream hover:bg-stone-100 dark:hover:bg-zinc-800 transition-colors">−</button>
                                <input type="number" name="quantity" id="qtyInput" value="1" min="1"
                                    class="w-14 h-12 text-center border-0 focus:outline-none font-semibold text-sm bg-transparent text-ink dark:text-cream">
                                <button type="button" onclick="incrementQty()"
                                    class="w-11 h-12 text-ink dark:text-cream hover:bg-stone-100 dark:hover:bg-zinc-800 transition-colors">+</button>
                            </div>

                            <button type="submit"
                                class="flex-1 h-12 font-display font-semibold text-sm tracking-wide text-white bg-gold hover:bg-forest transition-colors"
                                style="border-radius: 4px;">
                                أضف إلى السلة
                            </button>
                        </div>
                    </form>

                    {{-- JS for variants --}}
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
                            document.querySelectorAll('.product-image-dot').forEach((dot, i) => {
                                if (i === currentImageIndex) {
                                    dot.style.backgroundColor = '#ffffff';
                                    dot.style.width = '32px';
                                } else {
                                    dot.style.backgroundColor = 'rgba(255,255,255,0.4)';
                                    dot.style.width = '24px';
                                }
                            });
                        }

                        function nextProductImage() { goToProductImage(currentImageIndex + 1); }
                        function prevProductImage() { goToProductImage(currentImageIndex - 1); }

                        function selectColor(index) {
                            selectedColorIndex = index;
                            selectedSize = null;
                            document.querySelectorAll('.color-circle').forEach((circle, i) => {
                                if (i === index) {
                                    circle.classList.add('scale-110');
                                    circle.style.borderColor = '#14b8a6';
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
                                sizesContainer.innerHTML = '<p class="text-xs text-ink-faint dark:text-cream/40">لا توجد مقاسات لهذا اللون</p>';
                                return;
                            }
                            colorVariants.forEach(variant => {
                                const btn = document.createElement('button');
                                btn.type = 'button';
                                btn.dataset.variantId = variant.id;
                                btn.dataset.size = variant.size;
                                btn.disabled = variant.stock <= 0;
                                const baseStyle = 'h-10 px-4 text-xs font-semibold border transition-all duration-200';
                                if (variant.stock <= 0) {
                                    btn.className = baseStyle + ' border-stone-200 dark:border-stone-800 text-ink-faint dark:text-cream/30 cursor-not-allowed line-through';
                                    btn.style.borderRadius = '4px';
                                    btn.textContent = variant.size;
                                } else {
                                    btn.className = baseStyle + ' border-stone-300 dark:border-stone-700 hover:border-gold hover:text-gold';
                                    btn.style.borderRadius = '4px';
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
                                b.style.borderColor = '';
                                b.style.backgroundColor = '';
                                b.style.color = '';
                                b.classList.remove('bg-gold', 'text-white', 'border-gold');
                            });
                            btn.style.borderColor = '#14b8a6';
                            btn.style.backgroundColor = 'rgba(20, 184, 166, 0.1)';
                            btn.style.color = '#14b8a6';
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
                            if (parseInt(input.value) > 1) input.value = parseInt(input.value) - 1;
                        }
                    </script>
                @else
                    <div class="px-4 py-3 text-sm border border-amber-200 dark:border-amber-900/50 bg-amber-50 dark:bg-amber-950/20 text-amber-800 dark:text-amber-300"
                        style="border-radius: 4px;">
                        هذا المنتج غير متوفر حالياً بمتغيرات.
                    </div>
                @endif

                {{-- Trust indicators --}}
                <div class="grid grid-cols-3 gap-4 pt-8 border-t border-stone-200 dark:border-stone-800">
                    @foreach([
                            ['title' => 'شحن سريع', 'path' => 'M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0'],
                            ['title' => 'دفع آمن', 'path' => 'M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z'],
                            ['title' => 'إرجاع مجاني', 'path' => 'M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15'],
                        ] as $trust)
                        <div class="text-center">
                            <svg class="w-5 h-5 mx-auto mb-2 text-forest dark:text-gold" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="{{ $trust['path'] }}" />
                            </svg>
                            <p class="text-[10px] font-medium tracking-widest uppercase text-ink-muted dark:text-cream/50">
                                {{ $trust['title'] }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- ════════════ REVIEWS ════════════ --}}
        <section class="mb-24" id="reviews-section">
            <div class="mb-12 pb-6 border-b border-stone-200 dark:border-stone-800">
                <span class="eyebrow block mb-3">— 02 / التقييمات</span>
                <h2 class="display-2">
                    آراء <span class="text-forest dark:text-gold">العملاء</span>
                </h2>
            </div>

            <div class="grid lg:grid-cols-12 gap-10 lg:gap-16">

                {{-- Summary --}}
                <div class="lg:col-span-4">
                    <div class="border border-stone-200 dark:border-stone-800 p-8" style="border-radius: 4px;">
                        @php
                            $avg = $product->reviews->avg('rating') ?? 0;
                            $total = $product->reviews->count();
                        @endphp

                        <div class="text-center mb-8">
                            <div class="font-display text-6xl font-bold mb-3 text-forest dark:text-gold">
                                {{ number_format($avg, 1) }}
                            </div>
                            <div class="flex justify-center gap-1 mb-3 text-gold text-lg">
                                @for($i = 1; $i <= 5; $i++)
                                    <span>{{ $i <= round($avg) ? '★' : '☆' }}</span>
                                @endfor
                            </div>
                            <p class="text-xs tracking-widest uppercase text-ink-muted dark:text-cream/50">
                                {{ $total }} تقييم
                            </p>
                        </div>

                        <div class="space-y-3 pt-6 border-t border-stone-200 dark:border-stone-800">
                            @for($i = 5; $i >= 1; $i--)
                                @php
                                    $count = $product->reviews->where('rating', $i)->count();
                                    $percent = $total > 0 ? ($count / $total) * 100 : 0;
                                @endphp
                                <div class="flex items-center gap-3 text-xs">
                                    <span class="w-4 text-ink-muted dark:text-cream/50">{{ $i }}</span>
                                    <div class="flex-1 h-1 bg-stone-100 dark:bg-stone-800 overflow-hidden"
                                        style="border-radius: 2px;">
                                        <div class="h-full bg-gold" style="width: {{ $percent }}%; border-radius: 2px;"></div>
                                    </div>
                                    <span class="w-6 text-left text-ink-faint dark:text-cream/40">{{ $count }}</span>
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
                                    class="w-full mt-8 h-11 font-display font-semibold text-xs tracking-widest uppercase text-white bg-forest hover:bg-gold transition-colors"
                                    style="border-radius: 4px;">
                                    أضف تقييمك
                                </button>
                            @else
                                <div class="mt-8 px-4 py-3 text-xs text-center border border-green-200 dark:border-green-900/50 bg-green-50 dark:bg-green-950/20 text-green-800 dark:text-green-300"
                                    style="border-radius: 4px;">
                                    تم تقييم هذا المنتج
                                </div>
                            @endif
                        @else
                            <a href="{{ route('login') }}"
                                class="block w-full mt-8 h-11 font-display font-semibold text-xs tracking-widest uppercase text-center leading-[2.75rem] border border-stone-300 dark:border-stone-700 hover:border-forest transition-colors"
                                style="border-radius: 4px;">
                                سجّل للتقييم
                            </a>
                        @endauth
                    </div>
                </div>

                {{-- Reviews List --}}
                <div class="lg:col-span-8">
                    @auth
                        @php
                            $userReviewed = \App\Models\Review::where('user_id', auth()->id())
                                ->where('product_id', $product->id)->exists();
                        @endphp
                        @if(!$userReviewed)
                            <div id="reviewForm" class="hidden border-2 border-forest dark:border-gold p-6 mb-6"
                                style="border-radius: 4px;">
                                <h3 class="font-display text-lg font-bold mb-5">شاركنا رأيك</h3>

                                <form onsubmit="submitReview(event)" id="reviewFormElement">
                                    <input type="hidden" name="product_id" value="{{ $product->id }}">

                                    <div class="mb-5">
                                        <label
                                            class="block text-[10px] font-semibold tracking-widest uppercase text-ink-muted dark:text-cream/50 mb-3">
                                            تقييمك
                                        </label>
                                        <div class="flex gap-1 text-3xl" id="ratingStars">
                                            @for($i = 1; $i <= 5; $i++)
                                                <button type="button" onclick="setRating({{ $i }})" data-star="{{ $i }}"
                                                    class="rating-star transition-colors"
                                                    style="color: var(--border-medium);">★</button>
                                            @endfor
                                        </div>
                                        <input type="hidden" name="rating" id="ratingInput" value="0">
                                    </div>

                                    <div class="mb-5">
                                        <label
                                            class="block text-[10px] font-semibold tracking-widest uppercase text-ink-muted dark:text-cream/50 mb-3">
                                            تعليقك <span class="normal-case tracking-normal opacity-60">(اختياري)</span>
                                        </label>
                                        <textarea name="comment" rows="3" placeholder="اكتب تجربتك مع هذا المنتج..."
                                            class="w-full px-4 py-3 text-sm bg-transparent border border-stone-300 dark:border-stone-700 focus:border-forest focus:outline-none text-ink dark:text-cream transition-colors"
                                            style="border-radius: 4px;"></textarea>
                                    </div>

                                    <div class="flex gap-3">
                                        <button type="submit"
                                            class="h-11 px-6 font-display font-semibold text-xs tracking-widest uppercase text-white bg-forest hover:bg-gold transition-colors"
                                            style="border-radius: 4px;">
                                            إرسال
                                        </button>
                                        <button type="button" onclick="hideReviewForm()"
                                            class="h-11 px-6 font-display font-semibold text-xs tracking-widest uppercase border border-stone-300 dark:border-stone-700 hover:border-forest transition-colors"
                                            style="border-radius: 4px;">
                                            إلغاء
                                        </button>
                                    </div>
                                </form>
                            </div>
                        @endif
                    @endauth

                    <div id="reviewsList" class="space-y-4">
                        @forelse($product->reviews as $review)
                            <div class="border border-stone-200 dark:border-stone-800 p-6 review-item"
                                style="border-radius: 4px;">
                                <div class="flex items-start gap-4 mb-4">
                                    <div class="w-11 h-11 flex items-center justify-center text-white font-display font-bold text-sm shrink-0 bg-forest dark:bg-gold dark:text-ink"
                                        style="border-radius: 4px;">
                                        {{ mb_substr($review->user->name ?? 'U', 0, 1, 'UTF-8') }}
                                    </div>
                                    <div class="flex-1">
                                        <div class="flex items-center justify-between gap-3 flex-wrap">
                                            <div>
                                                <p class="font-semibold text-sm">{{ $review->user->name ?? 'مستخدم' }}</p>
                                                <p class="text-xs text-ink-faint dark:text-cream/40 mt-0.5">
                                                    {{ $review->created_at->format('Y-m-d') }}</p>
                                            </div>
                                            <div class="flex gap-0.5 text-gold">
                                                @for($i = 1; $i <= 5; $i++)
                                                    <span class="text-sm">{{ $i <= $review->rating ? '★' : '☆' }}</span>
                                                @endfor
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @if($review->comment)
                                    <p class="text-sm leading-relaxed text-ink-muted dark:text-cream/70">{{ $review->comment }}</p>
                                @endif
                            </div>
                        @empty
                            <div class="border border-stone-200 dark:border-stone-800 p-16 text-center"
                                style="border-radius: 4px;">
                                <div class="w-16 h-16 mx-auto mb-6 flex items-center justify-center border border-stone-300 dark:border-stone-700"
                                    style="border-radius: 4px;">
                                    <svg class="w-7 h-7 text-ink-faint dark:text-cream/30" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24" stroke-width="1.2">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                                    </svg>
                                </div>
                                <p class="text-sm text-ink-muted dark:text-cream/50">لا توجد تقييمات بعد. كن أول من يقيّم هذا
                                    المنتج</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </section>

        {{-- ════════════ RELATED PRODUCTS ════════════ --}}
        @if($relatedProducts->count())
            <section class="mb-24">
                <div class="flex items-end justify-between mb-12 pb-6 border-b border-stone-200 dark:border-stone-800">
                    <div>
                        <span class="eyebrow block mb-3">— 03 / اقتراحات</span>
                        <h2 class="display-2">منتجات <span class="text-forest dark:text-gold">مشابهة</span></h2>
                    </div>
                </div>
                <div class="grid grid-cols-2 lg:grid-cols-4 gap-x-4 gap-y-12 md:gap-x-6">
                    @foreach($relatedProducts as $relatedProduct)
                        @include('layouts.partials.product-card', ['product' => $relatedProduct])
                    @endforeach
                </div>
            </section>
        @endif

        {{-- ════════════ RECENTLY VIEWED ════════════ --}}
        @if(isset($recentlyViewed) && $recentlyViewed->count())
            <section>
                <div class="flex items-end justify-between mb-12 pb-6 border-b border-stone-200 dark:border-stone-800">
                    <div>
                        <span class="eyebrow block mb-3">— 04 / تصفحك السابق</span>
                        <h2 class="display-2">تصفّحت <span class="text-forest dark:text-gold">مؤخراً</span></h2>
                    </div>
                    <a href="{{ route('products.recentlyViewed') }}" class="link-arrow hidden sm:inline-flex">
                        عرض الكل
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                        </svg>
                    </a>
                </div>
                <div class="grid grid-cols-2 lg:grid-cols-4 gap-x-4 gap-y-12 md:gap-x-6">
                    @foreach($recentlyViewed as $recentProduct)
                        @include('layouts.partials.product-card', ['product' => $recentProduct])
                    @endforeach
                </div>
            </section>
        @endif

    </div>

    {{-- ════════════ FULLSCREEN MODAL ════════════ --}}
    <div id="imageFullscreenModal"
        class="hidden fixed inset-0 z-[9999] flex items-center justify-center p-4 bg-black/95 backdrop-blur-lg"
        onclick="if(event.target === this) closeFullscreen()">
        <button type="button" onclick="closeFullscreen()" title="إغلاق"
            class="absolute top-6 right-6 z-10 w-11 h-11 flex items-center justify-center text-cream bg-white/10 hover:bg-white/25 transition-colors"
            style="border-radius: 4px;">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
        <img id="fullscreenImage" src="" alt="" class="max-w-full max-h-full object-contain" style="direction: ltr;"
            onclick="event.stopPropagation()">
        <p class="absolute bottom-6 left-1/2 -translate-x-1/2 text-cream/60 text-xs">اضغط ESC أو انقر خارج الصورة للإغلاق
        </p>
    </div>

    {{-- ════════════ SIZE GUIDE MODAL ════════════ --}}
    @if($product->sizeGuide && $product->sizeGuide->is_active)
        <div id="sizeGuideModal" class="hidden fixed inset-0 bg-black/60 z-50 flex items-center justify-center p-4"
            onclick="if(event.target === this) closeSizeGuide()">
            <div class="max-w-2xl w-full max-h-[90vh] overflow-y-auto bg-canvas dark:bg-zinc-950 shadow-2xl"
                style="border-radius: 4px;" onclick="event.stopPropagation()">

                <div
                    class="sticky top-0 p-6 flex items-center justify-between border-b border-stone-200 dark:border-stone-800 bg-canvas dark:bg-zinc-950">
                    <div>
                        <h3 class="font-display text-lg font-bold">{{ $product->sizeGuide->name }}</h3>
                        @if($product->sizeGuide->description)
                            <p class="text-xs mt-1 text-ink-muted dark:text-cream/60">{{ $product->sizeGuide->description }}</p>
                        @endif
                    </div>
                    <button onclick="closeSizeGuide()"
                        class="w-10 h-10 flex items-center justify-center text-ink dark:text-cream hover:text-red-600 transition-colors shrink-0"
                        style="border-radius: 4px;">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <div class="p-6">
                    @if($product->sizeGuide->items->count())
                        <div class="overflow-x-auto border border-stone-200 dark:border-stone-800" style="border-radius: 4px;">
                            <table class="w-full text-sm">
                                <thead>
                                    <tr class="bg-stone-50 dark:bg-zinc-900">
                                        <th class="p-4 text-right font-semibold text-xs tracking-widest uppercase">المقاس</th>
                                        <th class="p-4 text-center font-semibold text-xs tracking-widest uppercase">الصدر</th>
                                        <th class="p-4 text-center font-semibold text-xs tracking-widest uppercase">الخصر</th>
                                        <th class="p-4 text-center font-semibold text-xs tracking-widest uppercase">الأرداف</th>
                                        <th class="p-4 text-center font-semibold text-xs tracking-widest uppercase">الطول</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($product->sizeGuide->items as $item)
                                        <tr class="border-t border-stone-200 dark:border-stone-800">
                                            <td class="p-4 text-right">
                                                <span
                                                    class="inline-block px-3 py-1 text-xs font-bold text-white bg-forest dark:bg-gold dark:text-ink"
                                                    style="border-radius: 2px;">
                                                    {{ $item->size }}
                                                </span>
                                            </td>
                                            <td class="p-4 text-center text-ink-muted dark:text-cream/70">{{ $item->chest ?? '—' }}</td>
                                            <td class="p-4 text-center text-ink-muted dark:text-cream/70">{{ $item->waist ?? '—' }}</td>
                                            <td class="p-4 text-center text-ink-muted dark:text-cream/70">{{ $item->hips ?? '—' }}</td>
                                            <td class="p-4 text-center text-ink-muted dark:text-cream/70">{{ $item->length ?? '—' }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-6 p-4 text-xs leading-relaxed border border-stone-200 dark:border-stone-800 bg-stone-50 dark:bg-zinc-900 text-ink-muted dark:text-cream/60"
                            style="border-radius: 4px;">
                            قِس نفسك بشريط قياس ناعم، وضع الشريط بشكل مريح على الجسم دون شد زائد.
                        </div>
                    @else
                        <p class="text-center py-12 text-sm text-ink-faint dark:text-cream/40">لا توجد تفاصيل مقاسات لهذا الدليل.
                        </p>
                    @endif
                </div>
            </div>
        </div>
    @endif

    {{-- ════════════ SCRIPTS ════════════ --}}
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
            document.querySelectorAll('.rating-star').forEach(s => { s.style.color = 'var(--border-medium)'; });
        }

        function setRating(value) {
            document.getElementById('ratingInput').value = value;
            document.querySelectorAll('.rating-star').forEach(star => {
                star.style.color = parseInt(star.dataset.star) <= value ? '#14b8a6' : 'var(--border-medium)';
            });
        }

        async function submitReview(event) {
            event.preventDefault();
            const form = event.target;
            const formData = new FormData(form);
            const rating = formData.get('rating');
            if (rating == 0) { showToast('الرجاء اختيار التقييم بالنجوم', 'error'); return; }

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
                if (!response.ok) { showToast(result.message || 'حدث خطأ', 'error'); return; }

                const reviewsList = document.getElementById('reviewsList');
                const emptyState = reviewsList.querySelector('.text-center');
                if (emptyState) emptyState.closest('.border').remove();

                const reviewHtml = `
                        <div class="border border-stone-200 dark:border-stone-800 p-6 review-item" style="border-radius: 4px;">
                            <div class="flex items-start gap-4 mb-4">
                                <div class="w-11 h-11 flex items-center justify-center text-white font-display font-bold text-sm shrink-0 bg-forest dark:bg-gold dark:text-ink" style="border-radius: 4px;">
                                    ${result.review.user_initial}
                                </div>
                                <div class="flex-1">
                                    <div class="flex items-center justify-between gap-3 flex-wrap">
                                        <div>
                                            <p class="font-semibold text-sm">${result.review.user_name}</p>
                                            <p class="text-xs text-ink-faint dark:text-cream/40 mt-0.5">${result.review.created_at}</p>
                                        </div>
                                        <div class="flex gap-0.5 text-gold">
                                            ${'★'.repeat(result.review.rating)}${'☆'.repeat(5 - result.review.rating)}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            ${result.review.comment ? `<p class="text-sm leading-relaxed text-ink-muted dark:text-cream/70">${result.review.comment}</p>` : ''}
                        </div>
                    `;
                reviewsList.insertAdjacentHTML('afterbegin', reviewHtml);

                const formDiv = document.getElementById('reviewForm');
                if (formDiv) formDiv.remove();

                showToast(result.message, 'success');
            } catch (error) {
                showToast('حدث خطأ، حاول مرة أخرى', 'error');
            }
        }

        function openSizeGuide() {
            const modal = document.getElementById('sizeGuideModal');
            if (modal) { modal.classList.remove('hidden'); document.body.style.overflow = 'hidden'; }
        }
        function closeSizeGuide() {
            const modal = document.getElementById('sizeGuideModal');
            if (modal) { modal.classList.add('hidden'); document.body.style.overflow = ''; }
        }

        function openFullscreen(imageSrc) {
            const modal = document.getElementById('imageFullscreenModal');
            const img = document.getElementById('fullscreenImage');
            img.src = imageSrc;
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
            requestAnimationFrame(() => { modal.style.opacity = '1'; });
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
            if (e.key === 'Escape') { closeSizeGuide(); closeFullscreen(); }
            if (e.key === 'ArrowRight') { if (typeof prevProductImage === 'function') prevProductImage(); }
            if (e.key === 'ArrowLeft') { if (typeof nextProductImage === 'function') nextProductImage(); }
        });
    </script>

@endsection