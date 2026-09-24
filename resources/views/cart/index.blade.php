@extends('layouts.app')

@section('title', 'سلة المشتريات | متجري')

@section('content')

    <style>
        .cart-page {
            --gold: #14b8a6;
            --gold-soft: rgba(20, 184, 166, 0.1);
            --border-light: #e6e6e4;
            --text-primary: #0f0f0f;
            --text-secondary: #6b6b5e;
            --text-tertiary: #9a9a8c;
            --bg-primary: #fafaf9;
            --bg-tertiary: #f5f5f4;
        }
        .dark .cart-page {
            --gold: #2dd4bf;
            --gold-soft: rgba(45, 212, 191, 0.15);
            --border-light: #262626;
            --text-primary: #f5f5f0;
            --text-secondary: #969690;
            --text-tertiary: #6e6e64;
            --bg-primary: #0a0a0a;
            --bg-tertiary: #141414;
        }
    </style>

    <div class="cart-page container-x pt-12 lg:pt-16 pb-24">

        {{-- ════════════ HEADER ════════════ --}}
        <header class="mb-12 lg:mb-16 pb-8 border-b border-stone-200 dark:border-stone-800">
            <nav class="flex items-center gap-3 text-[10px] font-medium uppercase tracking-[0.2em] text-ink-faint dark:text-cream/40 mb-8" aria-label="breadcrumb">
                <a href="{{ route('home') }}" class="hover:text-forest dark:hover:text-gold transition-colors">الرئيسية</a>
                <span class="opacity-40">/</span>
                <span class="text-ink dark:text-cream">السلة</span>
            </nav>

            <span class="eyebrow block mb-4">— سلة المشتريات</span>
            <h1 class="display-2">
                مراجعة
                <span class="text-forest dark:text-gold">طلبك</span>
            </h1>
            <p class="mt-4 text-base text-ink-muted dark:text-cream/60 text-pretty max-w-md">
                راجع المنتجات قبل إتمام الطلب.
            </p>
        </header>

        {{-- Session messages --}}
        @if(session('success'))
            <div class="px-5 py-4 mb-8 text-sm border border-green-200 dark:border-green-900/50 bg-green-50 dark:bg-green-950/20 text-green-800 dark:text-green-300 flex items-center gap-3" style="border-radius: 4px;">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="px-5 py-4 mb-8 text-sm border border-red-200 dark:border-red-900/50 bg-red-50 dark:bg-red-950/20 text-red-800 dark:text-red-300 flex items-center gap-3" style="border-radius: 4px;">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
                {{ session('error') }}
            </div>
        @endif

        @if(count($items) > 0)

            <div class="grid lg:grid-cols-12 gap-10 lg:gap-16">

                {{-- ════════════ ITEMS + COUPON ════════════ --}}
                <div class="lg:col-span-7 space-y-8">

                    {{-- Items List --}}
                    <div class="border border-stone-200 dark:border-stone-800" style="border-radius: 4px;">

                        <div class="flex items-center justify-between px-6 py-5 border-b border-stone-200 dark:border-stone-800">
                            <span class="text-[10px] font-semibold tracking-widest uppercase text-ink-muted dark:text-cream/60">
                                المنتجات
                            </span>
                            <span class="text-xs font-bold text-forest dark:text-gold">
                                {{ count($items) }}
                            </span>
                        </div>

                        <div class="divide-y divide-stone-200 dark:divide-stone-800">
                            @foreach($items as $item)
                                <div class="p-6 flex gap-5">

                                    {{-- Image --}}
                                    <a href="{{ route('products.show', $item['product']) }}"
                                       class="w-24 h-24 shrink-0 overflow-hidden bg-cream-warm dark:bg-zinc-900"
                                       style="border-radius: 4px;">
                                        @if($item['product']->images->first())
                                            <img src="{{ asset('storage/' . $item['product']->images->first()->image_path) }}"
                                                 alt="{{ $item['product']->name }}"
                                                 class="w-full h-full object-cover hover:scale-105 transition-transform duration-500">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center text-ink-faint dark:text-cream/30">
                                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                                </svg>
                                            </div>
                                        @endif
                                    </a>

                                    {{-- Info --}}
                                    <div class="flex-1 min-w-0">
                                        <a href="{{ route('products.show', $item['product']) }}"
                                           class="block font-display text-base font-bold mb-2 hover:text-forest dark:hover:text-gold transition-colors line-clamp-2">
                                            {{ $item['product']->name }}
                                        </a>

                                        @if($item['variant']->color || $item['variant']->size)
                                            <p class="text-xs text-ink-muted dark:text-cream/50 mb-3">
                                                @if($item['variant']->color)<span>{{ $item['variant']->color }}</span>@endif
                                                @if($item['variant']->color && $item['variant']->size)<span class="mx-1 opacity-40">·</span>@endif
                                                @if($item['variant']->size)<span>{{ $item['variant']->size }}</span>@endif
                                            </p>
                                        @endif

                                        <p class="font-display text-lg font-bold text-forest dark:text-gold">
                                            ${{ number_format($item['product']->discount_price ?? $item['product']->price, 2) }}
                                        </p>
                                    </div>

                                    {{-- Quantity + Remove --}}
                                    <div class="flex flex-col justify-between items-end gap-3 shrink-0">

                                        {{-- Remove --}}
                                        <form action="{{ route('cart.remove') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="variant_id" value="{{ $item['variant_id'] }}">
                                            <button type="submit" title="حذف"
                                                    class="w-9 h-9 flex items-center justify-center text-ink-faint dark:text-cream/40 hover:text-red-600 dark:hover:text-red-400 hover:bg-red-50 dark:hover:bg-red-950/30 transition-colors"
                                                    style="border-radius: 4px;">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </form>

                                        {{-- Quantity --}}
                                        <form action="{{ route('cart.update') }}" method="POST"
                                              class="flex items-center border border-stone-300 dark:border-stone-700 bg-canvas"
                                              style="border-radius: 4px;">
                                            @csrf
                                            <input type="hidden" name="variant_id" value="{{ $item['variant_id'] }}">
                                            <button type="submit" name="quantity" value="{{ $item['quantity'] - 1 }}"
                                                    class="w-9 h-9 flex items-center justify-center text-base font-medium text-ink dark:text-cream hover:bg-stone-100 dark:hover:bg-zinc-800 transition-colors">
                                                −
                                            </button>
                                            <span class="w-10 text-center text-sm font-display font-bold">
                                                {{ $item['quantity'] }}
                                            </span>
                                            <button type="submit" name="quantity" value="{{ $item['quantity'] + 1 }}"
                                                    class="w-9 h-9 flex items-center justify-center text-base font-medium text-ink dark:text-cream hover:bg-stone-100 dark:hover:bg-zinc-800 transition-colors">
                                                +
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- Coupon --}}
                    <div class="border border-stone-200 dark:border-stone-800 p-6" style="border-radius: 4px;">
                        <label class="block text-[10px] font-semibold tracking-widest uppercase text-ink-muted dark:text-cream/60 mb-4">
                            كود الخصم
                        </label>

                        @if($coupon)
                            <div class="flex items-center justify-between p-4 border border-green-200 dark:border-green-900/50 bg-green-50 dark:bg-green-950/20" style="border-radius: 4px;">
                                <div class="flex items-center gap-4">
                                    <div class="w-10 h-10 flex items-center justify-center bg-green-600 dark:bg-green-500 text-white shrink-0" style="border-radius: 4px;">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="font-display font-bold text-sm text-green-800 dark:text-green-300">{{ $coupon->code }}</p>
                                        <p class="text-xs text-green-700 dark:text-green-400 mt-0.5">
                                            @if($coupon->type === 'percentage')
                                                خصم {{ $coupon->value }}%
                                            @else
                                                خصم ${{ number_format($coupon->value, 2) }}
                                            @endif
                                            @if($coupon->free_shipping)
                                                <span class="mx-1 opacity-50">·</span> شحن مجاني
                                            @endif
                                        </p>
                                    </div>
                                </div>
                                <form action="{{ route('coupon.remove') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="text-xs font-semibold text-red-600 dark:text-red-400 hover:opacity-70 transition-opacity">
                                        إزالة
                                    </button>
                                </form>
                            </div>
                        @else
                            <form action="{{ route('coupon.apply') }}" method="POST" class="flex gap-2">
                                @csrf
                                <input type="text" name="code" required placeholder="أدخل الكود..."
                                       class="flex-1 h-12 px-4 text-sm bg-transparent border border-stone-300 dark:border-stone-700 focus:border-forest dark:focus:border-gold focus:outline-none text-ink dark:text-cream uppercase tracking-wider transition-colors"
                                       style="border-radius: 4px;">
                                <button type="submit"
                                        class="h-12 px-6 font-display font-bold text-xs tracking-widest uppercase text-white bg-forest dark:bg-gold dark:text-ink hover:opacity-90 transition-opacity"
                                        style="border-radius: 4px;">
                                    تطبيق
                                </button>
                            </form>
                        @endif
                    </div>

                    {{-- Continue shopping --}}
                    <a href="{{ route('products.index') }}" class="link-arrow inline-flex">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                        </svg>
                        متابعة التسوق
                    </a>
                </div>

                {{-- ════════════ SUMMARY ════════════ --}}
                <div class="lg:col-span-5">
                    <div class="sticky top-24 border border-stone-200 dark:border-stone-800 p-8" style="border-radius: 4px;">

                        <span class="eyebrow block mb-6">— ملخص الطلب</span>

                        <div class="space-y-4 pb-6 mb-6 border-b border-stone-200 dark:border-stone-800">
                            <div class="flex justify-between text-sm">
                                <span class="text-ink-muted dark:text-cream/60">المجموع الفرعي</span>
                                <span class="font-medium">${{ number_format($total, 2) }}</span>
                            </div>

                            @if($discount > 0)
                                <div class="flex justify-between text-sm">
                                    <span class="text-ink-muted dark:text-cream/60">الخصم ({{ $coupon->code }})</span>
                                    <span class="font-medium text-green-600 dark:text-green-400">−${{ number_format($discount, 2) }}</span>
                                </div>
                            @endif

                            <div class="flex justify-between text-sm">
                                <span class="text-ink-muted dark:text-cream/60">الشحن</span>
                                @if($selectedCity)
                                    @if($shipping > 0)
                                        <span class="font-medium">${{ number_format($shipping, 2) }}</span>
                                    @else
                                        <span class="font-medium text-green-600 dark:text-green-400">مجاني</span>
                                    @endif
                                @else
                                    <span class="text-xs text-ink-faint dark:text-cream/40">يُحدد في الدفع</span>
                                @endif
                            </div>
                        </div>

                        <div class="flex items-baseline justify-between mb-8">
                            <span class="font-display text-base font-bold">الإجمالي</span>
                            <span class="font-display text-3xl font-bold text-forest dark:text-gold">
                                ${{ number_format($finalTotal, 2) }}
                            </span>
                        </div>

                        <a href="{{ route('checkout.index') }}"
                           class="group flex items-center justify-center gap-3 w-full h-14 font-display font-bold text-sm tracking-widest uppercase text-white bg-forest dark:bg-gold dark:text-ink hover:bg-gold dark:hover:bg-forest dark:hover:text-cream transition-all"
                           style="border-radius: 4px;">
                            إتمام الطلب
                            <svg class="w-4 h-4 transition-transform group-hover:-translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                            </svg>
                        </a>

                        {{-- Trust indicators --}}
                        <div class="mt-8 pt-6 border-t border-stone-200 dark:border-stone-800 space-y-3">
                            @foreach([
                                    ['title' => 'دفع آمن 100%', 'path' => 'M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z'],
                                    ['title' => 'إرجاع مجاني خلال 14 يوم', 'path' => 'M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15'],
                                    ['title' => 'شحن سريع لكل المدن', 'path' => 'M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0'],
                                ] as $trust)
                                    <div class="flex items-center gap-3 text-xs text-ink-muted dark:text-cream/60">
                                        <svg class="w-4 h-4 text-forest dark:text-gold shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="{{ $trust['path'] }}" />
                                        </svg>
                                        <span>{{ $trust['title'] }}</span>
                                    </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

        @else
            {{-- ════════════ EMPTY CART ════════════ --}}
            <div class="text-center py-20 max-w-lg mx-auto">

                <div class="w-24 h-24 mx-auto mb-8 flex items-center justify-center border border-stone-300 dark:border-stone-700" style="border-radius: 4px;">
                    <svg class="w-10 h-10 text-ink-faint dark:text-cream/30" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                    </svg>
                </div>

                <span class="eyebrow block mb-4">— السلة فارغة</span>

                <h2 class="display-2 mb-4">
                    لم تُضف أي
                    <span class="text-forest dark:text-gold">منتج بعد</span>
                </h2>

                <p class="text-base text-ink-muted dark:text-cream/60 mb-10 text-pretty">
                    ابدأ التسوّق واكتشف منتجاتنا المختارة بعناية.
                </p>

                <a href="{{ route('products.index') }}" class="btn-solid group">
                    ابدأ التسوق
                    <svg class="w-4 h-4 transition-transform group-hover:-translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                    </svg>
                </a>
            </div>
        @endif

    </div>

@endsection