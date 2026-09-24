@extends('layouts.app')

@section('title', 'المنتجات المشاهدة حديثاً | متجري')

@section('content')

<div class="container-x pt-12 lg:pt-16 pb-24">

    {{-- ════════════ HEADER ════════════ --}}
    <header class="mb-12 lg:mb-16 pb-8 border-b border-stone-200 dark:border-stone-800">
        <nav class="flex items-center gap-3 text-[10px] font-medium uppercase tracking-[0.2em] text-ink-faint dark:text-cream/40 mb-8" aria-label="breadcrumb">
            <a href="{{ route('home') }}" class="hover:text-forest dark:hover:text-gold transition-colors">الرئيسية</a>
            <span class="opacity-40">/</span>
            <a href="{{ route('products.index') }}" class="hover:text-forest dark:hover:text-gold transition-colors">المتجر</a>
            <span class="opacity-40">/</span>
            <span class="text-ink dark:text-cream">المشاهدة حديثاً</span>
        </nav>

        <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-6">
            <div>
                <span class="eyebrow block mb-4">— سجل التصفح</span>
                <h1 class="display-2 mb-4 text-balance">
                    تصفّحت
                    <span class="text-forest dark:text-gold">مؤخراً</span>
                </h1>
                <p class="text-base text-ink-muted dark:text-cream/60 text-pretty max-w-md">
                    @if($products->count())
                        آخر {{ $products->count() }} {{ $products->count() === 1 ? 'منتج' : 'منتجات' }} قمت بتصفحها.
                    @else
                        آخر المنتجات التي تصفحتها ستظهر هنا.
                    @endif
                </p>
            </div>

            @if($products->count())
                <a href="{{ route('products.index') }}" class="link-arrow shrink-0">
                    تصفح المزيد
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                    </svg>
                </a>
            @endif
        </div>
    </header>

    @if($products->count())

        {{-- ════════════ GRID ════════════ --}}
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-x-4 gap-y-12 md:gap-x-6 md:gap-y-16">
            @foreach($products as $product)
                @include('layouts.partials.product-card', ['product' => $product])
            @endforeach
        </div>

    @else

        {{-- ════════════ EMPTY STATE ════════════ --}}
        <div class="text-center py-20 max-w-lg mx-auto">

            <div class="w-24 h-24 mx-auto mb-8 flex items-center justify-center border border-stone-300 dark:border-stone-700" style="border-radius: 4px;">
                <svg class="w-10 h-10 text-ink-faint dark:text-cream/30" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                </svg>
            </div>

            <span class="eyebrow block mb-4">— السجل فارغ</span>

            <h2 class="display-2 mb-4 text-balance">
                لم تتصفح أي
                <span class="text-forest dark:text-gold">منتج بعد</span>
            </h2>

            <p class="text-base text-ink-muted dark:text-cream/60 mb-10 text-pretty">
                ابدأ باستكشاف منتجاتنا، وسنحتفظ بسجل تصفحك هنا.
            </p>

            <a href="{{ route('products.index') }}" class="btn-solid group">
                تصفح المنتجات
                <svg class="w-4 h-4 transition-transform group-hover:-translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                </svg>
            </a>
        </div>

    @endif

</div>

@endsection