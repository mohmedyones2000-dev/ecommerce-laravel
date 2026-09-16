<footer class="mt-20 border-t" style="background-color: var(--bg-primary); border-color: var(--border-light);">
    <div class="max-w-[1400px] mx-auto px-4 sm:px-6 lg:px-8 py-14">
        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-8 lg:gap-10">

            <div class="col-span-2 md:col-span-4 lg:col-span-2">
                <a href="{{ route('home') }}" class="flex items-center gap-2.5 mb-4">
                    @php
                        $siteSettings = \App\Models\SiteSetting::current();
                    @endphp

                    @if($siteSettings->logo)
                        <img src="{{ asset('storage/' . $siteSettings->logo) }}" alt="{{ $siteSettings->site_name }}"
                            class="w-9 h-9 rounded-lg object-cover">
                    @else
                        <div class="w-9 h-9 rounded-lg flex items-center justify-center text-white text-sm font-bold"
                            style="background-color: var(--gold);">
                            {{ mb_substr($siteSettings->site_name ?? 'م', 0, 1, 'UTF-8') }}
                        </div>
                    @endif
                    <span class="text-base font-bold tracking-tight" style="color: var(--text-primary);">متجري</span>
                </a>
                <p class="text-[13px] leading-relaxed max-w-sm" style="color: var(--text-secondary);">
                    تجربة تسوق عصرية بأفضل الأسعار وأجود المنتجات. نوصل طلبك إلى باب منزلك.
                </p>

                @php $settings = \App\Models\SiteSetting::current(); @endphp

                @if($settings->facebook || $settings->instagram || $settings->twitter || $settings->whatsapp)
                    <div class="flex items-center gap-2 mt-5">
                        @if($settings->facebook)
                            <a href="{{ $settings->facebook }}" target="_blank" rel="noopener"
                                class="flex items-center justify-center w-9 h-9 rounded-lg transition-colors duration-150"
                                style="background-color: var(--bg-tertiary); color: var(--text-secondary);"
                                onmouseover="this.style.backgroundColor='var(--gold)'; this.style.color='white';"
                                onmouseout="this.style.backgroundColor='var(--bg-tertiary)'; this.style.color='var(--text-secondary)';">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                    <path
                                        d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z" />
                                </svg>
                            </a>
                        @endif

                        @if($settings->instagram)
                            <a href="{{ $settings->instagram }}" target="_blank" rel="noopener"
                                class="flex items-center justify-center w-9 h-9 rounded-lg transition-colors duration-150"
                                style="background-color: var(--bg-tertiary); color: var(--text-secondary);"
                                onmouseover="this.style.backgroundColor='var(--gold)'; this.style.color='white';"
                                onmouseout="this.style.backgroundColor='var(--bg-tertiary)'; this.style.color='var(--text-secondary)';">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                    <path
                                        d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z" />
                                </svg>
                            </a>
                        @endif

                        @if($settings->twitter)
                            <a href="{{ $settings->twitter }}" target="_blank" rel="noopener"
                                class="flex items-center justify-center w-9 h-9 rounded-lg transition-colors duration-150"
                                style="background-color: var(--bg-tertiary); color: var(--text-secondary);"
                                onmouseover="this.style.backgroundColor='var(--gold)'; this.style.color='white';"
                                onmouseout="this.style.backgroundColor='var(--bg-tertiary)'; this.style.color='var(--text-secondary)';">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                    <path
                                        d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z" />
                                </svg>
                            </a>
                        @endif

                        @if($settings->whatsapp)
                            <a href="https://wa.me/{{ $settings->whatsapp }}" target="_blank" rel="noopener"
                                class="flex items-center justify-center w-9 h-9 rounded-lg transition-colors duration-150"
                                style="background-color: var(--bg-tertiary); color: var(--text-secondary);"
                                onmouseover="this.style.backgroundColor='var(--gold)'; this.style.color='white';"
                                onmouseout="this.style.backgroundColor='var(--bg-tertiary)'; this.style.color='var(--text-secondary)';">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                    <path
                                        d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z" />
                                </svg>
                            </a>
                        @endif
                    </div>
                @endif
            </div>

            <div>
                <h3 class="text-[13px] font-semibold mb-4" style="color: var(--text-primary);">روابط سريعة</h3>
                <ul class="space-y-2.5">
                    <li>
                        <a href="{{ route('home') }}" class="text-[13px] transition-colors duration-150"
                            style="color: var(--text-secondary);" onmouseover="this.style.color='var(--gold)';"
                            onmouseout="this.style.color='var(--text-secondary)';">
                            الرئيسية
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('products.index') }}" class="text-[13px] transition-colors duration-150"
                            style="color: var(--text-secondary);" onmouseover="this.style.color='var(--gold)';"
                            onmouseout="this.style.color='var(--text-secondary)';">
                            المنتجات
                        </a>
                    </li>

                    @php
                        $quickLinks = \App\Models\Page::inFooter()
                            ->whereNotIn('slug', ['terms', 'privacy'])
                            ->get();
                    @endphp

                    @foreach($quickLinks as $page)
                        <li>
                            <a href="{{ url($page->slug) }}" class="text-[13px] transition-colors duration-150"
                                style="color: var(--text-secondary);" onmouseover="this.style.color='var(--gold)';"
                                onmouseout="this.style.color='var(--text-secondary)';">
                                {{ $page->title }}
                            </a>
                        </li>
                    @endforeach

                    <li>
                        <a href="{{ route('pages.contact') }}" class="text-[13px] transition-colors duration-150"
                            style="color: var(--text-secondary);" onmouseover="this.style.color='var(--gold)';"
                            onmouseout="this.style.color='var(--text-secondary)';">
                            اتصل بنا
                        </a>
                    </li>
                </ul>
            </div>

            <div>
                <h3 class="text-[13px] font-semibold mb-4" style="color: var(--text-primary);">خدمة العملاء</h3>
                <ul class="space-y-2.5">
                    <li>
                        <a href="{{ route('pages.faq') }}" class="text-[13px] transition-colors duration-150"
                            style="color: var(--text-secondary);" onmouseover="this.style.color='var(--gold)';"
                            onmouseout="this.style.color='var(--text-secondary)';">
                            الأسئلة الشائعة
                        </a>
                    </li>

                    @php
                        $legalPages = \App\Models\Page::inFooter()
                            ->whereIn('slug', ['terms', 'privacy'])
                            ->get();
                    @endphp

                    @foreach($legalPages as $page)
                        <li>
                            <a href="{{ url($page->slug) }}" class="text-[13px] transition-colors duration-150"
                                style="color: var(--text-secondary);" onmouseover="this.style.color='var(--gold)';"
                                onmouseout="this.style.color='var(--text-secondary)';">
                                {{ $page->title }}
                            </a>
                        </li>
                    @endforeach

                    <li>
                        <a href="{{ route('orders.index') }}" class="text-[13px] transition-colors duration-150"
                            style="color: var(--text-secondary);" onmouseover="this.style.color='var(--gold)';"
                            onmouseout="this.style.color='var(--text-secondary)';">
                            تتبع الطلبات
                        </a>
                    </li>
                </ul>
            </div>

            <div>
                <h3 class="text-[13px] font-semibold mb-4" style="color: var(--text-primary);">تواصل معنا</h3>
                <ul class="space-y-3">
                    <li class="flex items-start gap-2.5">
                        <svg class="w-4 h-4 mt-0.5 shrink-0" style="color: var(--gold);" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                        <span class="text-[13px] break-all"
                            style="color: var(--text-secondary);">{{ $settings->email }}</span>
                    </li>
                    <li class="flex items-start gap-2.5">
                        <svg class="w-4 h-4 mt-0.5 shrink-0" style="color: var(--gold);" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                        </svg>
                        <span class="text-[13px] ltr:text-left"
                            style="color: var(--text-secondary);">{{ $settings->phone }}</span>
                    </li>
                    @if($settings->address)
                        <li class="flex items-start gap-2.5">
                            <svg class="w-4 h-4 mt-0.5 shrink-0" style="color: var(--gold);" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            <span class="text-[13px]" style="color: var(--text-secondary);">{{ $settings->address }}</span>
                        </li>
                    @endif
                </ul>
            </div>
        </div>

        <div class="mt-12 pt-6 flex flex-col sm:flex-row items-center justify-between gap-4 border-t"
            style="border-color: var(--border-light);">
            <p class="text-[12px]" style="color: var(--text-tertiary);">
                © {{ date('Y') }} متجري. جميع الحقوق محفوظة.
            </p>

            <div class="flex items-center gap-4">
                <span class="text-[12px]" style="color: var(--text-tertiary);">طرق الدفع</span>
                <div class="flex items-center gap-2">
                    <div class="w-8 h-5 rounded flex items-center justify-center"
                        style="background-color: var(--bg-tertiary);">
                        <svg class="w-4 h-3" viewBox="0 0 24 16" fill="none">
                            <rect x="0" y="0" width="24" height="16" rx="2" fill="currentColor"
                                style="color: var(--text-tertiary);" />
                        </svg>
                    </div>
                    <div class="w-8 h-5 rounded flex items-center justify-center"
                        style="background-color: var(--bg-tertiary);">
                        <svg class="w-4 h-3" viewBox="0 0 24 16" fill="none">
                            <rect x="0" y="0" width="24" height="16" rx="2" fill="currentColor"
                                style="color: var(--text-tertiary);" />
                        </svg>
                    </div>
                    <div class="w-8 h-5 rounded flex items-center justify-center"
                        style="background-color: var(--bg-tertiary);">
                        <svg class="w-4 h-3" viewBox="0 0 24 16" fill="none">
                            <rect x="0" y="0" width="24" height="16" rx="2" fill="currentColor"
                                style="color: var(--text-tertiary);" />
                        </svg>
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>