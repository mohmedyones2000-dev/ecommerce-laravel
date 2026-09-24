@extends('layouts.app')

@section('title', $siteSettings->site_name ?? 'متجري')

@section('content')

    {{-- ==========================================
    HERO SECTION
    ========================================== --}}
    <section class="relative overflow-hidden bg-[rgb(var(--surface))] dark:bg-[rgb(var(--surface-soft))]" id="heroSlider">
        <div class="container-wide">
            <div class="relative h-[560px] md:h-[640px] lg:h-[680px]">

                {{-- Slide 1 --}}
                <div class="hero-slide absolute inset-0 transition-all duration-700 ease-out opacity-100 translate-y-0">
                    <div class="grid md:grid-cols-2 gap-10 lg:gap-16 items-center h-full">
                        <div class="space-y-6 lg:space-y-8 motion-safe:animate-fade-up">
                            <span
                                class="inline-flex items-center gap-2 text-xs font-semibold px-3.5 py-1.5 rounded-full bg-[rgb(var(--brand-500)/0.1)] text-[rgb(var(--brand-700))] dark:text-[rgb(var(--brand-300))]">
                                <span
                                    class="w-1.5 h-1.5 rounded-full bg-[rgb(var(--brand-500))] motion-safe:animate-pulse"></span>
                                عروض حصرية لفترة محدودة
                            </span>

                            <h1
                                class="text-4xl sm:text-5xl md:text-6xl lg:text-7xl font-black leading-[1.1] tracking-tight">
                                تسوّق أحدث المنتجات
                                <br>
                                <span class="text-gradient-brand">بأسعار لا تُقاوَم</span>
                            </h1>

                            <p class="text-base md:text-lg leading-relaxed max-w-md text-[rgb(var(--text-secondary))]">
                                اكتشف تشكيلة واسعة من المنتجات المختارة بعناية، مع شحن سريع وضمان الجودة.
                            </p>

                            <div class="flex flex-wrap gap-3 pt-2">
                                <a href="{{ route('products.index') }}" class="btn-primary group">
                                    تسوّق الآن
                                    <svg class="w-4 h-4 transition-transform group-hover:-translate-x-0.5" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                                    </svg>
                                </a>
                                <a href="#featured" class="btn-outline">
                                    اكتشف المزيد
                                </a>
                            </div>

                            {{-- Trust indicators --}}
                            <div class="flex flex-wrap gap-6 pt-6 border-t border-[rgb(var(--border))]">
                                <div class="flex items-center gap-2 text-sm text-[rgb(var(--text-secondary))]">
                                    <svg class="w-5 h-5 text-[rgb(var(--brand-500))]" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                    </svg>
                                    شحن سريع
                                </div>
                                <div class="flex items-center gap-2 text-sm text-[rgb(var(--text-secondary))]">
                                    <svg class="w-5 h-5 text-[rgb(var(--brand-500))]" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                    </svg>
                                    ضمان الجودة
                                </div>
                                <div class="flex items-center gap-2 text-sm text-[rgb(var(--text-secondary))]">
                                    <svg class="w-5 h-5 text-[rgb(var(--brand-500))]" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                    </svg>
                                    إرجاع مجاني
                                </div>
                            </div>
                        </div>

                        <div class="hidden md:block relative">
                            <div
                                class="absolute inset-0 bg-gradient-to-br from-[rgb(var(--brand-400)/0.3)] to-[rgb(var(--brand-600)/0.1)] rounded-[2.5rem] blur-3xl">
                            </div>
                            <div
                                class="relative aspect-square rounded-[2rem] bg-gradient-to-br from-[rgb(var(--brand-100))] to-[rgb(var(--brand-200))] dark:from-[rgb(var(--brand-900))] dark:to-[rgb(var(--brand-950))] flex items-center justify-center shadow-glow">
                                <svg class="w-40 h-40 text-[rgb(var(--brand-600))] dark:text-[rgb(var(--brand-400))]"
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="0.8">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Slide 2 --}}
                <div class="hero-slide absolute inset-0 transition-all duration-700 ease-out opacity-0 translate-y-4">
                    <div class="grid md:grid-cols-2 gap-10 lg:gap-16 items-center h-full">
                        <div class="space-y-6 lg:space-y-8">
                            <span
                                class="inline-flex items-center gap-2 text-xs font-semibold px-3.5 py-1.5 rounded-full bg-[rgb(var(--brand-500)/0.1)] text-[rgb(var(--brand-700))] dark:text-[rgb(var(--brand-300))]">
                                <span class="w-1.5 h-1.5 rounded-full bg-[rgb(var(--brand-500))]"></span>
                                شحن مجاني للمدن المختارة
                            </span>

                            <h1
                                class="text-4xl sm:text-5xl md:text-6xl lg:text-7xl font-black leading-[1.1] tracking-tight">
                                شحن سريع
                                <br>
                                <span class="text-gradient-brand">إلى باب منزلك</span>
                            </h1>

                            <p class="text-base md:text-lg leading-relaxed max-w-md text-[rgb(var(--text-secondary))]">
                                نوصل طلبك في أسرع وقت، مع إمكانية تتبع الطلب خطوة بخطوة.
                            </p>

                            <a href="{{ route('products.index') }}" class="btn-primary group inline-flex">
                                ابدأ التسوق
                                <svg class="w-4 h-4 transition-transform group-hover:-translate-x-0.5" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                                </svg>
                            </a>
                        </div>

                        <div class="hidden md:block relative">
                            <div
                                class="absolute inset-0 bg-gradient-to-br from-[rgb(var(--brand-400)/0.3)] to-[rgb(var(--brand-600)/0.1)] rounded-[2.5rem] blur-3xl">
                            </div>
                            <div
                                class="relative aspect-square rounded-[2rem] bg-gradient-to-br from-[rgb(var(--brand-100))] to-[rgb(var(--brand-200))] dark:from-[rgb(var(--brand-900))] dark:to-[rgb(var(--brand-950))] flex items-center justify-center">
                                <svg class="w-40 h-40 text-[rgb(var(--brand-600))] dark:text-[rgb(var(--brand-400))]"
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="0.8">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0" />
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Slide 3 --}}
                <div class="hero-slide absolute inset-0 transition-all duration-700 ease-out opacity-0 translate-y-4">
                    <div class="grid md:grid-cols-2 gap-10 lg:gap-16 items-center h-full">
                        <div class="space-y-6 lg:space-y-8">
                            <span
                                class="inline-flex items-center gap-2 text-xs font-semibold px-3.5 py-1.5 rounded-full bg-[rgb(var(--brand-500)/0.1)] text-[rgb(var(--brand-700))] dark:text-[rgb(var(--brand-300))]">
                                <span class="w-1.5 h-1.5 rounded-full bg-[rgb(var(--brand-500))]"></span>
                                خصومات تصل إلى 50%
                            </span>

                            <h1
                                class="text-4xl sm:text-5xl md:text-6xl lg:text-7xl font-black leading-[1.1] tracking-tight">
                                وفّر أكثر مع
                                <br>
                                <span class="text-gradient-brand">عروضنا الحصرية</span>
                            </h1>

                            <p class="text-base md:text-lg leading-relaxed max-w-md text-[rgb(var(--text-secondary))]">
                                خصومات حقيقية على مجموعة مختارة من المنتجات. لا تفوت الفرصة.
                            </p>

                            <a href="{{ route('products.index', ['has_discount' => 1]) }}"
                                class="btn-primary group inline-flex">
                                تصفح العروض
                                <svg class="w-4 h-4 transition-transform group-hover:-translate-x-0.5" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                                </svg>
                            </a>
                        </div>

                        <div class="hidden md:block relative">
                            <div
                                class="absolute inset-0 bg-gradient-to-br from-[rgb(var(--brand-400)/0.3)] to-[rgb(var(--brand-600)/0.1)] rounded-[2.5rem] blur-3xl">
                            </div>
                            <div
                                class="relative aspect-square rounded-[2rem] bg-gradient-to-br from-[rgb(var(--brand-100))] to-[rgb(var(--brand-200))] dark:from-[rgb(var(--brand-900))] dark:to-[rgb(var(--brand-950))] flex items-center justify-center">
                                <svg class="w-40 h-40 text-[rgb(var(--brand-600))] dark:text-[rgb(var(--brand-400))]"
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="0.8">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Dots --}}
                <div class="absolute bottom-6 left-1/2 -translate-x-1/2 flex gap-2 z-10">
                    <button onclick="goToSlide(0)"
                        class="hero-dot w-8 h-1.5 rounded-full transition-all duration-300 bg-[rgb(var(--brand-500))]"
                        data-slide="0" aria-label="الشريحة الأولى"></button>
                    <button onclick="goToSlide(1)"
                        class="hero-dot w-8 h-1.5 rounded-full transition-all duration-300 bg-[rgb(var(--border-strong))]"
                        data-slide="1" aria-label="الشريحة الثانية"></button>
                    <button onclick="goToSlide(2)"
                        class="hero-dot w-8 h-1.5 rounded-full transition-all duration-300 bg-[rgb(var(--border-strong))]"
                        data-slide="2" aria-label="الشريحة الثالثة"></button>
                </div>

                {{-- Arrows --}}
                <button onclick="prevSlide()"
                    class="absolute top-1/2 right-4 -translate-y-1/2 w-11 h-11 rounded-full bg-[rgb(var(--surface-elevated))] border border-[rgb(var(--border))] text-[rgb(var(--text-primary))] flex items-center justify-center hover:border-[rgb(var(--brand-500))] hover:text-[rgb(var(--brand-600))] transition-all z-10 shadow-soft"
                    aria-label="السابق">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                    </svg>
                </button>
                <button onclick="nextSlide()"
                    class="absolute top-1/2 left-4 -translate-y-1/2 w-11 h-11 rounded-full bg-[rgb(var(--surface-elevated))] border border-[rgb(var(--border))] text-[rgb(var(--text-primary))] flex items-center justify-center hover:border-[rgb(var(--brand-500))] hover:text-[rgb(var(--brand-600))] transition-all z-10 shadow-soft"
                    aria-label="التالي">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                    </svg>
                </button>
            </div>
        </div>
    </section>

    {{-- ==========================================
    CATEGORIES
    ========================================== --}}
    <section class="py-16 lg:py-20 animate-on-scroll">
        <div class="container-wide">
            <div class="flex items-end justify-between mb-8">
                <div>
                    <h2 class="section-title">تسوّق حسب التصنيف</h2>
                    <p class="section-subtitle mt-2">اختر من بين مجموعة واسعة من التصنيفات</p>
                </div>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3 lg:gap-4">
                @foreach($categories as $category)
                    <a href="{{ route('products.index', ['category' => $category->slug]) }}"
                        class="group flex flex-col items-center text-center p-5 lg:p-6 rounded-2xl border border-[rgb(var(--border))] bg-[rgb(var(--surface-elevated))] hover:border-[rgb(var(--brand-500))] hover:-translate-y-1 hover:shadow-soft transition-all duration-300">
                        <div
                            class="w-14 h-14 rounded-2xl flex items-center justify-center mb-3 bg-[rgb(var(--brand-500)/0.1)] text-[rgb(var(--brand-600))] dark:text-[rgb(var(--brand-400))] group-hover:bg-[rgb(var(--brand-500))] group-hover:text-white transition-all duration-300">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                            </svg>
                        </div>
                        <span class="text-sm font-semibold text-[rgb(var(--text-primary))]">{{ $category->name }}</span>
                    </a>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ==========================================
    FEATURED PRODUCTS
    ========================================== --}}
    @if($featuredProducts->count())
        <section class="py-16 lg:py-20 bg-[rgb(var(--surface))] dark:bg-[rgb(var(--surface-soft))] animate-on-scroll"
            id="featured">
            <div class="container-wide">
                <div class="flex items-end justify-between mb-8 lg:mb-10">
                    <div>
                        <h2 class="section-title">منتجات مميزة</h2>
                        <p class="section-subtitle mt-2">اختياراتنا المفضلة من أفضل المنتجات</p>
                    </div>
                    <a href="{{ route('products.index') }}"
                        class="hidden sm:inline-flex items-center gap-1.5 text-sm font-semibold text-[rgb(var(--brand-600))] dark:text-[rgb(var(--brand-400))] hover:gap-2.5 transition-all">
                        عرض الكل
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                        </svg>
                    </a>
                </div>

                <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 lg:gap-6">
                    @foreach($featuredProducts as $product)
                        @include('layouts.partials.product-card', ['product' => $product])
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- ==========================================
    BEST SELLERS
    ========================================== --}}
    @if($bestSellers->count())
        <section class="py-16 lg:py-20 animate-on-scroll">
            <div class="container-wide">
                <div class="flex items-end justify-between mb-8 lg:mb-10">
                    <div>
                        <h2 class="section-title">الأكثر مبيعاً</h2>
                        <p class="section-subtitle mt-2">المنتجات التي يحبها عملاؤنا</p>
                    </div>
                </div>

                <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 lg:gap-6">
                    @foreach($bestSellers as $product)
                        @include('layouts.partials.product-card', ['product' => $product])
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- ==========================================
    TOP RATED
    ========================================== --}}
    @if($topRated->count())
        <section class="py-16 lg:py-20 bg-[rgb(var(--surface))] dark:bg-[rgb(var(--surface-soft))] animate-on-scroll">
            <div class="container-wide">
                <div class="flex items-end justify-between mb-8 lg:mb-10">
                    <div>
                        <h2 class="section-title">الأعلى تقييماً</h2>
                        <p class="section-subtitle mt-2">المنتجات التي حازت على إعجاب عملائنا</p>
                    </div>
                </div>

                <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 lg:gap-6">
                    @foreach($topRated as $product)
                        @include('layouts.partials.product-card', ['product' => $product])
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- ==========================================
    LATEST PRODUCTS
    ========================================== --}}
    @if($latestProducts->count())
        <section class="py-16 lg:py-20 animate-on-scroll">
            <div class="container-wide">
                <div class="flex items-end justify-between mb-8 lg:mb-10">
                    <div>
                        <h2 class="section-title">وصل حديثاً</h2>
                        <p class="section-subtitle mt-2">أحدث المنتجات في متجرنا</p>
                    </div>
                    <a href="{{ route('products.index') }}"
                        class="hidden sm:inline-flex items-center gap-1.5 text-sm font-semibold text-[rgb(var(--brand-600))] dark:text-[rgb(var(--brand-400))] hover:gap-2.5 transition-all">
                        عرض الكل
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                        </svg>
                    </a>
                </div>

                <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 lg:gap-6">
                    @foreach($latestProducts as $product)
                        @include('layouts.partials.product-card', ['product' => $product])
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- ==========================================
    RECENTLY VIEWED
    ========================================== --}}
    @if(isset($recentlyViewed) && $recentlyViewed->count())
        <section class="py-16 lg:py-20 bg-[rgb(var(--surface))] dark:bg-[rgb(var(--surface-soft))] animate-on-scroll">
            <div class="container-wide">
                <div class="flex items-end justify-between mb-8 lg:mb-10">
                    <div>
                        <h2 class="section-title">تصفّحت مؤخراً</h2>
                        <p class="section-subtitle mt-2">عُد إلى المنتجات التي أعجبتك</p>
                    </div>
                    <a href="{{ route('products.recentlyViewed') }}"
                        class="hidden sm:inline-flex items-center gap-1.5 text-sm font-semibold text-[rgb(var(--brand-600))] dark:text-[rgb(var(--brand-400))] hover:gap-2.5 transition-all">
                        عرض الكل
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                        </svg>
                    </a>
                </div>

                <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 lg:gap-6">
                    @foreach($recentlyViewed as $product)
                        @include('layouts.partials.product-card', ['product' => $product])
                    @endforeach
                </div>
            </div>
        </section>
    @endif


    {{-- ==========================================
    HERO SLIDER JS
    ========================================== --}}
    @push('scripts')
        <script>
            (function () {
                let currentSlide = 0;
                const slides = document.querySelectorAll('.hero-slide');
                const dots = document.querySelectorAll('.hero-dot');
                const totalSlides = slides.length;
                if (!totalSlides) return;

                function goToSlide(index) {
                    slides.forEach((s, i) => {
                        if (i === index) {
                            s.classList.remove('opacity-0', 'translate-y-4');
                            s.classList.add('opacity-100', 'translate-y-0');
                        } else {
                            s.classList.add('opacity-0', 'translate-y-4');
                            s.classList.remove('opacity-100', 'translate-y-0');
                        }
                    });

                    dots.forEach((d, i) => {
                        d.classList.toggle('bg-[rgb(var(--brand-500))]', i === index);
                        d.classList.toggle('bg-[rgb(var(--border-strong))]', i !== index);
                    });

                    currentSlide = index;
                }

                window.goToSlide = goToSlide;
                window.nextSlide = () => goToSlide((currentSlide + 1) % totalSlides);
                window.prevSlide = () => goToSlide((currentSlide - 1 + totalSlides) % totalSlides);

                // Auto-play (respect reduced motion)
                if (!window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
                    setInterval(window.nextSlide, 6000);
                }
            })();
        </script>
    @endpush

@endsection