@extends('layouts.app')

@section('title', 'المفضلة | متجري')

@section('content')

    <div class="container-x pt-12 lg:pt-16 pb-24">

        {{-- ════════════ HEADER ════════════ --}}
        <header class="mb-12 lg:mb-16 pb-8 border-b border-stone-200 dark:border-stone-800">
            <nav class="flex items-center gap-3 text-[10px] font-medium uppercase tracking-[0.2em] text-ink-faint dark:text-cream/40 mb-8"
                aria-label="breadcrumb">
                <a href="{{ route('home') }}" class="hover:text-forest dark:hover:text-gold transition-colors">الرئيسية</a>
                <span class="opacity-40">/</span>
                <span class="text-ink dark:text-cream">المفضلة</span>
            </nav>

            <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-6">
                <div>
                    <span class="eyebrow block mb-4">— قائمتك المحفوظة</span>
                    <h1 class="display-2 mb-4 text-balance">
                        المنتجات
                        <span class="text-forest dark:text-gold">المفضلة</span>
                    </h1>
                    <p class="text-base text-ink-muted dark:text-cream/60 text-pretty max-w-md">
                        @if($wishlists->count())
                            لديك {{ $wishlists->count() }} {{ $wishlists->count() == 1 ? 'منتج' : 'منتجات' }} محفوظة في مفضلتك.
                        @else
                            احفظ منتجاتك المفضلة هنا للوصول إليها لاحقاً.
                        @endif
                    </p>
                </div>

                @if($wishlists->count())
                    <a href="{{ route('products.index') }}" class="link-arrow shrink-0">
                        تصفح المزيد
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                        </svg>
                    </a>
                @endif
            </div>
        </header>

        @if($wishlists->count())

            {{-- ════════════ GRID ════════════ --}}
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-x-4 gap-y-12 md:gap-x-6 md:gap-y-16" id="wishlistGrid">
                @foreach($wishlists as $wishlist)
                    @if($wishlist->product)
                        <div data-wishlist-item="{{ $wishlist->product->id }}">
                            @include('layouts.partials.product-card', ['product' => $wishlist->product])
                        </div>
                    @endif
                @endforeach
            </div>

        @else

            {{-- ════════════ EMPTY STATE ════════════ --}}
            <div class="text-center py-20 max-w-lg mx-auto">

                <div class="w-24 h-24 mx-auto mb-8 flex items-center justify-center border border-stone-300 dark:border-stone-700"
                    style="border-radius: 4px;">
                    <svg class="w-10 h-10 text-ink-faint dark:text-cream/30" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24" stroke-width="1">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                    </svg>
                </div>

                <span class="eyebrow block mb-4">— المفضلة فارغة</span>

                <h2 class="display-2 mb-4 text-balance">
                    لم تُضف أي
                    <span class="text-forest dark:text-gold">منتج بعد</span>
                </h2>

                <p class="text-base text-ink-muted dark:text-cream/60 mb-10 text-pretty">
                    ابدأ باستكشاف منتجاتنا واحفظ ما يعجبك بضغطة واحدة.
                </p>

                <a href="{{ route('products.index') }}" class="btn-solid group">
                    ابدأ التسوق
                    <svg class="w-4 h-4 transition-transform group-hover:-translate-x-1" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                    </svg>
                </a>
            </div>

        @endif

    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const observer = new MutationObserver(function () {
                document.querySelectorAll('[data-wishlist-item]').forEach(function (item) {
                    const wishlistIcon = item.querySelector('.wishlist-icon');
                    const btn = wishlistIcon?.closest('button');
                    if (btn && btn.dataset.inWishlist === '0') {
                        item.remove();
                    }
                });
            });

            const grid = document.getElementById('wishlistGrid');
            if (grid) {
                observer.observe(grid, { childList: true, subtree: true });
            }
        });
    </script>

@endsection