@php $settings = \App\Models\SiteSetting::current(); @endphp

<footer class="mt-32 text-cream bg-forest">
    <div class="container-x py-20">

        <div class="grid lg:grid-cols-2 gap-12 pb-16 border-b border-white/10">
            <div>
                <span class="eyebrow" style="color: rgba(255,255,255,0.5);">— النشرة البريدية</span>
                <h2 class="display-2 text-cream mt-6">ابق على تواصل<br>مع كل جديد</h2>
            </div>
            <div class="lg:pt-16">
                <p class="text-cream/60 mb-6">اشترك لتصلك أحدث المنتجات والعروض.</p>
                <form id="newsletterForm" onsubmit="return submitNewsletter(event)" class="flex flex-col sm:flex-row gap-3 max-w-lg">
                    <input type="email" id="newsletterEmail" required placeholder="بريدك الإلكتروني" class="flex-1 bg-transparent border-b border-white/30 focus:border-white text-cream placeholder:text-white/40 py-3 px-1 text-sm focus:outline-none">
                    <button type="submit" id="newsletterBtn" class="text-xs font-bold tracking-widest uppercase px-6 py-3 bg-cream text-ink hover:bg-white transition-colors" style="border-radius: 4px;">
                        <span id="newsletterBtnText">اشترك</span>
                        <svg id="newsletterSpinner" class="hidden animate-spin h-3 w-3 inline-block mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg>
                    </button>
                </form>
                <div id="newsletterMessage" class="hidden mt-4 text-sm"></div>
            </div>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-4 gap-10 py-16 border-b border-white/10">
            <div class="col-span-2 md:col-span-1">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-10 h-10 bg-cream text-ink flex items-center justify-center font-bold" style="border-radius: 4px;">{{ mb_substr($settings->site_name ?? 'م', 0, 1, 'UTF-8') }}</div>
                    <span class="font-bold text-lg tracking-tight">{{ $settings->site_name ?? 'متجري' }}</span>
                </div>
                <p class="text-cream/60 text-sm leading-relaxed max-w-xs">تجربة تسوق عصرية بأفضل الأسعار وأجود المنتجات.</p>
            </div>

            <div>
                <h4 class="text-xs font-bold tracking-[0.2em] uppercase text-cream/40 mb-6">تسوّق</h4>
                <ul class="space-y-3 text-sm">
                    <li><a href="{{ route('home') }}" class="text-cream/70 hover:text-white">الرئيسية</a></li>
                    <li><a href="{{ route('products.index') }}" class="text-cream/70 hover:text-white">المتجر</a></li>
                    <li><a href="{{ route('products.index', ['has_discount' => 1]) }}" class="text-cream/70 hover:text-white">عروض</a></li>
                    @foreach(\App\Models\Page::inFooter()->take(3)->get() as $page)
                        <li><a href="{{ url($page->slug) }}" class="text-cream/70 hover:text-white">{{ $page->title }}</a></li>
                    @endforeach
                </ul>
            </div>

            <div>
                <h4 class="text-xs font-bold tracking-[0.2em] uppercase text-cream/40 mb-6">مساعدة</h4>
                <ul class="space-y-3 text-sm">
                    <li><a href="{{ route('pages.faq') }}" class="text-cream/70 hover:text-white">الأسئلة الشائعة</a></li>
                    <li><a href="{{ route('orders.index') }}" class="text-cream/70 hover:text-white">تتبع الطلبات</a></li>
                    <li><a href="{{ route('pages.contact') }}" class="text-cream/70 hover:text-white">اتصل بنا</a></li>
                </ul>
            </div>

            <div>
                <h4 class="text-xs font-bold tracking-[0.2em] uppercase text-cream/40 mb-6">تواصل</h4>
                <ul class="space-y-4 text-sm">
                    @if($settings->email)<li><a href="mailto:{{ $settings->email }}" class="text-cream/70 hover:text-white break-all">{{ $settings->email }}</a></li>@endif
                    @if($settings->phone)<li><a href="tel:{{ $settings->phone }}" class="text-cream/70 hover:text-white" dir="ltr">{{ $settings->phone }}</a></li>@endif
                    @if($settings->address)<li class="text-cream/70">{{ $settings->address }}</li>@endif
                </ul>
                @if($settings->facebook || $settings->instagram || $settings->twitter)
                    <div class="flex gap-2 mt-6">
                        @foreach([['url' => $settings->facebook, 'path' => 'M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z'], ['url' => $settings->instagram, 'path' => 'M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z'], ['url' => $settings->twitter, 'path' => 'M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z']] as $s)
                            @if($s['url'])
                                <a href="{{ $s['url'] }}" target="_blank" rel="noopener" class="w-10 h-10 flex items-center justify-center border border-white/20 text-cream/70 hover:bg-white hover:text-ink transition-all" style="border-radius: 4px;">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="{{ $s['path'] }}"/></svg>
                                </a>
                            @endif
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

        <div class="flex flex-col md:flex-row justify-between items-center gap-4 pt-8 text-xs text-cream/40">
            <p>© {{ date('Y') }} {{ $settings->site_name ?? 'متجري' }} — جميع الحقوق محفوظة</p>
            <p class="tracking-widest uppercase">Made with care</p>
        </div>
    </div>
</footer>