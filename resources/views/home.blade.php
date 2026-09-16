@extends('layouts.app')

@section('title', 'متجري')

@section('content')

    <section class="relative overflow-hidden" id="heroSlider">
        <div class="relative h-[520px] md:h-[580px]">

            <div class="hero-slide absolute inset-0 transition-opacity duration-700 opacity-100" style="background-color: var(--bg-tertiary);">
                <div class="max-w-[1400px] mx-auto px-4 sm:px-6 lg:px-8 h-full">
                    <div class="grid md:grid-cols-2 gap-10 items-center h-full">
                        <div>
                            <span class="inline-block text-[12px] font-semibold px-3 py-1.5 rounded-full mb-5"
                                  style="background-color: var(--gold-soft); color: var(--gold);">
                                عروض حصرية لفترة محدودة
                            </span>
                            <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold leading-tight mb-5" style="color: var(--text-primary);">
                                تسوّق أحدث المنتجات
                                <br>
                                <span style="color: var(--gold);">بأسعار لا تُقاوَم</span>
                            </h1>
                            <p class="text-base md:text-lg mb-8 leading-relaxed max-w-md" style="color: var(--text-secondary);">
                                اكتشف تشكيلة واسعة من المنتجات المختارة بعناية، مع شحن سريع وضمان الجودة.
                            </p>
                            <div class="flex flex-wrap gap-3">
                                <a href="{{ route('products.index') }}"
                                   class="inline-flex items-center gap-2 px-6 py-3 rounded-lg font-semibold text-white text-[14px] transition-all duration-200 hover:opacity-90"
                                   style="background-color: var(--gold);">
                                    تسوّق الآن
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                                    </svg>
                                </a>
                                <a href="#featured"
                                   class="inline-flex items-center px-6 py-3 rounded-lg font-semibold text-[14px] transition-all duration-200"
                                   style="background-color: var(--bg-primary); color: var(--text-primary); border: 1px solid var(--border-light);">
                                    اكتشف المزيد
                                </a>
                            </div>
                        </div>
                        <div class="hidden md:flex justify-center">
                            <div class="w-72 h-72 rounded-full flex items-center justify-center" style="background-color: var(--gold-soft);">
                                <svg class="w-32 h-32" style="color: var(--gold);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="hero-slide absolute inset-0 transition-opacity duration-700 opacity-0" style="background-color: var(--gold-soft);">
                <div class="max-w-[1400px] mx-auto px-4 sm:px-6 lg:px-8 h-full">
                    <div class="grid md:grid-cols-2 gap-10 items-center h-full">
                        <div>
                            <span class="inline-block text-[12px] font-semibold px-3 py-1.5 rounded-full mb-5"
                                  style="background-color: var(--bg-primary); color: var(--gold);">
                                شحن مجاني للمدن المختارة
                            </span>
                            <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold leading-tight mb-5" style="color: var(--text-primary);">
                                شحن سريع
                                <br>
                                <span style="color: var(--gold);">إلى باب منزلك</span>
                            </h1>
                            <p class="text-base md:text-lg mb-8 leading-relaxed max-w-md" style="color: var(--text-secondary);">
                                نوصل طلبك في أسرع وقت، مع إمكانية تتبع الطلب خطوة بخطوة.
                            </p>
                            <a href="{{ route('products.index') }}"
                               class="inline-flex items-center gap-2 px-6 py-3 rounded-lg font-semibold text-white text-[14px] transition-all duration-200 hover:opacity-90"
                               style="background-color: var(--gold);">
                                ابدأ التسوق
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                                </svg>
                            </a>
                        </div>
                        <div class="hidden md:flex justify-center">
                            <div class="w-72 h-72 rounded-full flex items-center justify-center" style="background-color: var(--bg-primary);">
                                <svg class="w-32 h-32" style="color: var(--gold);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0" />
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="hero-slide absolute inset-0 transition-opacity duration-700 opacity-0" style="background-color: var(--bg-tertiary);">
                <div class="max-w-[1400px] mx-auto px-4 sm:px-6 lg:px-8 h-full">
                    <div class="grid md:grid-cols-2 gap-10 items-center h-full">
                        <div>
                            <span class="inline-block text-[12px] font-semibold px-3 py-1.5 rounded-full mb-5"
                                  style="background-color: var(--gold-soft); color: var(--gold);">
                                خصومات تصل إلى 50%
                            </span>
                            <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold leading-tight mb-5" style="color: var(--text-primary);">
                                وفّر أكثر مع
                                <br>
                                <span style="color: var(--gold);">عروضنا الحصرية</span>
                            </h1>
                            <p class="text-base md:text-lg mb-8 leading-relaxed max-w-md" style="color: var(--text-secondary);">
                                خصومات حقيقية على مجموعة مختارة من المنتجات. لا تفوت الفرصة.
                            </p>
                            <a href="{{ route('products.index', ['has_discount' => 1]) }}"
                               class="inline-flex items-center gap-2 px-6 py-3 rounded-lg font-semibold text-white text-[14px] transition-all duration-200 hover:opacity-90"
                               style="background-color: var(--gold);">
                                تصفح العروض
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                                </svg>
                            </a>
                        </div>
                        <div class="hidden md:flex justify-center">
                            <div class="w-72 h-72 rounded-full flex items-center justify-center" style="background-color: var(--gold-soft);">
                                <svg class="w-32 h-32" style="color: var(--gold);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="absolute bottom-6 left-1/2 -translate-x-1/2 flex gap-2 z-10">
                <button onclick="goToSlide(0)" class="hero-dot w-8 h-1 rounded-full transition-all duration-300" style="background-color: var(--gold);" data-slide="0"></button>
                <button onclick="goToSlide(1)" class="hero-dot w-8 h-1 rounded-full transition-all duration-300" style="background-color: var(--border-medium);" data-slide="1"></button>
                <button onclick="goToSlide(2)" class="hero-dot w-8 h-1 rounded-full transition-all duration-300" style="background-color: var(--border-medium);" data-slide="2"></button>
            </div>

            <button onclick="prevSlide()"
                    class="absolute top-1/2 right-4 -translate-y-1/2 w-10 h-10 rounded-full flex items-center justify-center transition-all duration-200 z-10"
                    style="background-color: var(--bg-primary); border: 1px solid var(--border-light); color: var(--text-primary);">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </button>
            <button onclick="nextSlide()"
                    class="absolute top-1/2 left-4 -translate-y-1/2 w-10 h-10 rounded-full flex items-center justify-center transition-all duration-200 z-10"
                    style="background-color: var(--bg-primary); border: 1px solid var(--border-light); color: var(--text-primary);">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
            </button>
        </div>
    </section>

    <section class="py-14 animate-on-scroll">
        <div class="max-w-[1400px] mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-end justify-between mb-8">
                <div>
                    <h2 class="text-2xl font-bold mb-1" style="color: var(--text-primary);">تسوّق حسب التصنيف</h2>
                    <p class="text-[13px]" style="color: var(--text-secondary);">اختر من بين مجموعة واسعة من التصنيفات</p>
                </div>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-3">
                @foreach($categories as $category)
                    <a href="{{ route('products.index', ['category' => $category->slug]) }}"
                       class="group flex flex-col items-center text-center p-5 rounded-xl border transition-all duration-200"
                       style="background-color: var(--bg-primary); border-color: var(--border-light);"
                       onmouseover="this.style.borderColor='var(--gold)'; this.style.transform='translateY(-2px)';"
                       onmouseout="this.style.borderColor='var(--border-light)'; this.style.transform='translateY(0)';">
                        <div class="w-12 h-12 rounded-lg flex items-center justify-center mb-3 transition-all duration-200"
                             style="background-color: var(--gold-soft); color: var(--gold);">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                            </svg>
                        </div>
                        <span class="text-[13px] font-medium" style="color: var(--text-primary);">{{ $category->name }}</span>
                    </a>
                @endforeach
            </div>
        </div>
    </section>

    @if($featuredProducts->count())
        <section class="py-14 animate-on-scroll" id="featured">
            <div class="max-w-[1400px] mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex items-end justify-between mb-8">
                    <div>
                        <h2 class="text-2xl font-bold mb-1" style="color: var(--text-primary);">منتجات مميزة</h2>
                        <p class="text-[13px]" style="color: var(--text-secondary);">اختياراتنا المفضلة من أفضل المنتجات</p>
                    </div>
                    <a href="{{ route('products.index') }}" class="text-[13px] font-semibold flex items-center gap-1 transition-colors duration-150"
                       style="color: var(--gold);">
                        عرض الكل
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                    </a>
                </div>

                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-5">
                    @foreach($featuredProducts as $product)
                        @include('layouts.partials.product-card', ['product' => $product])
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    @if($bestSellers->count())
        <section class="py-14 animate-on-scroll">
            <div class="max-w-[1400px] mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex items-end justify-between mb-8">
                    <div>
                        <h2 class="text-2xl font-bold mb-1" style="color: var(--text-primary);">الأكثر مبيعاً</h2>
                        <p class="text-[13px]" style="color: var(--text-secondary);">المنتجات التي يحبها عملاؤنا</p>
                    </div>
                </div>

                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-5">
                    @foreach($bestSellers as $product)
                        @include('layouts.partials.product-card', ['product' => $product])
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    @if($topRated->count())
        <section class="py-14 animate-on-scroll">
            <div class="max-w-[1400px] mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex items-end justify-between mb-8">
                    <div>
                        <h2 class="text-2xl font-bold mb-1" style="color: var(--text-primary);">الأعلى تقييماً</h2>
                        <p class="text-[13px]" style="color: var(--text-secondary);">المنتجات التي حازت على إعجاب عملائنا</p>
                    </div>
                </div>

                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-5">
                    @foreach($topRated as $product)
                        @include('layouts.partials.product-card', ['product' => $product])
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    @if($latestProducts->count())
        <section class="py-14 animate-on-scroll">
            <div class="max-w-[1400px] mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex items-end justify-between mb-8">
                    <div>
                        <h2 class="text-2xl font-bold mb-1" style="color: var(--text-primary);">وصل حديثاً</h2>
                        <p class="text-[13px]" style="color: var(--text-secondary);">أحدث المنتجات في متجرنا</p>
                    </div>
                    <a href="{{ route('products.index') }}" class="text-[13px] font-semibold flex items-center gap-1 transition-colors duration-150"
                       style="color: var(--gold);">
                        عرض الكل
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                    </a>
                </div>

                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-5">
                    @foreach($latestProducts as $product)
                        @include('layouts.partials.product-card', ['product' => $product])
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    @if(isset($recentlyViewed) && $recentlyViewed->count())
        <section class="py-14 animate-on-scroll">
            <div class="max-w-[1400px] mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex items-end justify-between mb-8">
                    <div>
                        <h2 class="text-2xl font-bold mb-1" style="color: var(--text-primary);">تصفّحت مؤخراً</h2>
                        <p class="text-[13px]" style="color: var(--text-secondary);">عُد إلى المنتجات التي أعجبتك</p>
                    </div>
                    <a href="{{ route('products.recentlyViewed') }}" class="text-[13px] font-semibold flex items-center gap-1 transition-colors duration-150"
                       style="color: var(--gold);">
                        عرض الكل
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                    </a>
                </div>

                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-5">
                    @foreach($recentlyViewed as $product)
                        @include('layouts.partials.product-card', ['product' => $product])
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <section class="py-14 animate-on-scroll">
        <div class="max-w-[1400px] mx-auto px-4 sm:px-6 lg:px-8">
            <div class="rounded-2xl p-8 md:p-12 text-center" style="background-color: var(--gold-soft);">
                <div class="w-14 h-14 rounded-xl flex items-center justify-center mx-auto mb-5" style="background-color: var(--gold);">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                </div>

                <h2 class="text-2xl md:text-3xl font-bold mb-2" style="color: var(--text-primary);">اشترك في نشرتنا البريدية</h2>
                <p class="text-[14px] mb-7 max-w-md mx-auto" style="color: var(--text-secondary);">احصل على آخر العروض والمنتجات الجديدة في بريدك</p>

                <form id="newsletterForm" class="max-w-md mx-auto flex flex-col sm:flex-row gap-2" onsubmit="return submitNewsletter(event)">
                    <input type="email" name="email" id="newsletterEmail" required
                           placeholder="بريدك الإلكتروني"
                           class="flex-1 h-11 px-4 rounded-lg text-[14px] focus:outline-none focus:ring-2 transition-all duration-150"
                           style="background-color: var(--bg-primary); color: var(--text-primary); border: 1px solid var(--border-light); --tw-ring-color: var(--gold);">
                    <button type="submit" id="newsletterBtn"
                            class="h-11 px-6 rounded-lg font-semibold text-[14px] text-white transition-all duration-200 hover:opacity-90 flex items-center justify-center gap-2 whitespace-nowrap"
                            style="background-color: var(--gold);">
                        <span id="newsletterBtnText">اشترك</span>
                        <svg id="newsletterSpinner" class="hidden animate-spin h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </button>
                </form>

                <div id="newsletterMessage" class="hidden max-w-md mx-auto mt-3 px-4 py-2.5 rounded-lg text-[13px]"></div>

                <p class="text-[11px] mt-4" style="color: var(--text-tertiary);">
                    لن نشارك بريدك مع أي طرف ثالث
                </p>
            </div>
        </div>
    </section>

    <script>
        let currentSlide = 0;
        const slides = document.querySelectorAll('.hero-slide');
        const dots = document.querySelectorAll('.hero-dot');
        const totalSlides = slides.length;

        function goToSlide(index) {
            slides.forEach(s => s.classList.replace('opacity-100', 'opacity-0'));
            dots.forEach(d => d.style.backgroundColor = 'var(--border-medium)');

            slides[index].classList.replace('opacity-0', 'opacity-100');
            dots[index].style.backgroundColor = 'var(--gold)';

            currentSlide = index;
        }

        function nextSlide() {
            const next = (currentSlide + 1) % totalSlides;
            goToSlide(next);
        }

        function prevSlide() {
            const prev = (currentSlide - 1 + totalSlides) % totalSlides;
            goToSlide(prev);
        }

        setInterval(nextSlide, 6000);
    </script>

@endsection