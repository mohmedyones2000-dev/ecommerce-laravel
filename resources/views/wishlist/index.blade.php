@extends('layouts.app')

@section('title', 'المفضلة | متجري')

@section('content')

    <div class="max-w-[1400px] mx-auto px-4 sm:px-6 lg:px-8 py-8">

        <div class="mb-8">
            <h1 class="text-2xl md:text-3xl font-bold mb-1.5" style="color: var(--text-primary);">المفضلة</h1>
            <p class="text-[13px]" style="color: var(--text-secondary);">
                {{ $wishlists->count() }} {{ $wishlists->count() == 1 ? 'منتج' : 'منتجات' }} في المفضلة
            </p>
        </div>

        @if($wishlists->count())

            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-5" id="wishlistGrid">
                @foreach($wishlists as $wishlist)
                    @if($wishlist->product)
                        <div data-wishlist-item="{{ $wishlist->product->id }}">
                            @include('layouts.partials.product-card', ['product' => $wishlist->product])
                        </div>
                    @endif
                @endforeach
            </div>

        @else

            <div class="rounded-xl border p-14 text-center max-w-lg mx-auto"
                style="background-color: var(--bg-primary); border-color: var(--border-light);">
                <div class="w-16 h-16 rounded-xl flex items-center justify-center mx-auto mb-5"
                    style="background-color: var(--bg-tertiary);">
                    <svg class="w-8 h-8" style="color: var(--text-tertiary);" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                    </svg>
                </div>
                <h2 class="text-lg font-bold mb-2" style="color: var(--text-primary);">المفضلة فارغة</h2>
                <p class="text-[13px] mb-6" style="color: var(--text-secondary);">
                    لم تقم بإضافة أي منتجات إلى المفضلة بعد
                </p>
                <a href="{{ route('products.index') }}"
                    class="inline-flex items-center gap-2 h-11 px-6 rounded-lg font-semibold text-[13px] text-white transition-all duration-200 hover:opacity-90"
                    style="background-color: var(--gold);">
                    ابدأ التسوق
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </a>
            </div>

        @endif

    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const observer = new MutationObserver(function () {
                document.querySelectorAll('[data-wishlist-item]').forEach(function (item) {
                    const wishlistBtn = item.querySelector('.wishlist-icon');

                    if (wishlistBtn && wishlistBtn.closest('button')?.dataset.inWishlist === '0') {
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