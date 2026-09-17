<header class="sticky top-0 z-50 bg-white/95 backdrop-blur-md border-b border-gray-200">
    <div class="max-w-[1400px] mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16 gap-4">

            <a href="{{ route('home') }}" class="flex items-center gap-2.5 shrink-0">
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

            <nav class="hidden lg:flex items-center gap-1">
                <a href="{{ route('home') }}"
                    class="px-3 py-2 text-[13px] font-medium rounded-lg transition-colors duration-150"
                    style="color: {{ request()->routeIs('home') ? 'var(--gold)' : 'var(--text-secondary)' }}; background-color: {{ request()->routeIs('home') ? 'var(--gold-soft)' : 'transparent' }};"
                    onmouseover="if('{{ request()->routeIs('home') ? '1' : '0' }}'==='0') this.style.backgroundColor='var(--bg-tertiary)'; this.style.color='var(--text-primary)';"
                    onmouseout="if('{{ request()->routeIs('home') ? '1' : '0' }}'==='0') this.style.backgroundColor='transparent'; this.style.color='var(--text-secondary)';">
                    الرئيسية
                </a>
                <a href="{{ route('products.index') }}"
                    class="px-3 py-2 text-[13px] font-medium rounded-lg transition-colors duration-150"
                    style="color: {{ request()->routeIs('products.index') ? 'var(--gold)' : 'var(--text-secondary)' }}; background-color: {{ request()->routeIs('products.index') ? 'var(--gold-soft)' : 'transparent' }};"
                    onmouseover="if('{{ request()->routeIs('products.index') ? '1' : '0' }}'==='0') this.style.backgroundColor='var(--bg-tertiary)'; this.style.color='var(--text-primary)';"
                    onmouseout="if('{{ request()->routeIs('products.index') ? '1' : '0' }}'==='0') this.style.backgroundColor='transparent'; this.style.color='var(--text-secondary)';">
                    المنتجات
                </a>
                <a href="{{ route('products.index', ['has_discount' => 1]) }}"
                    class="px-3 py-2 text-[13px] font-medium rounded-lg transition-colors duration-150"
                    style="color: var(--text-secondary);"
                    onmouseover="this.style.backgroundColor='var(--bg-tertiary)'; this.style.color='var(--text-primary)';"
                    onmouseout="this.style.backgroundColor='transparent'; this.style.color='var(--text-secondary)';">
                    عروض
                </a>
                <a href="{{ route('pages.contact') }}"
                    class="px-3 py-2 text-[13px] font-medium rounded-lg transition-colors duration-150"
                    style="color: var(--text-secondary);"
                    onmouseover="this.style.backgroundColor='var(--bg-tertiary)'; this.style.color='var(--text-primary)';"
                    onmouseout="this.style.backgroundColor='transparent'; this.style.color='var(--text-secondary)';">
                    اتصل بنا
                </a>
            </nav>

            <div class="hidden lg:block flex-1 max-w-xs relative" x-data="{ 
                    query: '', 
                    results: [], 
                    total: 0,
                    loading: false,
                    isOpen: false,
                    async search() {
                        if (this.query.length < 2) {
                            this.results = [];
                            this.isOpen = false;
                            return;
                        }
                        this.loading = true;
                        try {
                            const res = await fetch(`{{ route('products.search') }}?q=${encodeURIComponent(this.query)}`);
                            const data = await res.json();
                            this.results = data.products;
                            this.total = data.total;
                            this.isOpen = true;
                        } catch (e) {
                            console.error(e);
                        }
                        this.loading = false;
                    }
                }" @click.away="isOpen = false">

                <form action="{{ route('products.index') }}" method="GET">
                    <div class="relative">
                        <input type="text" name="search" x-model="query" @input.debounce.300ms="search()"
                            @focus="if (query.length >= 2 && results.length > 0) isOpen = true"
                            placeholder="ابحث عن منتج" autocomplete="off"
                            class="w-full h-9 bg-gray-100 border-0 rounded-lg pr-9 pl-3 text-[13px] focus:outline-none focus:ring-2 focus:bg-white transition-all duration-150"
                            style="--tw-ring-color: var(--gold);">
                        <svg class="w-4 h-4 absolute top-1/2 -translate-y-1/2 right-3 pointer-events-none"
                            style="color: var(--text-tertiary);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                </form>

                <div x-show="isOpen" x-cloak x-transition:enter="transition ease-out duration-150"
                    x-transition:enter-start="opacity-0 -translate-y-1"
                    x-transition:enter-end="opacity-100 translate-y-0"
                    class="absolute top-full mt-2 right-0 left-0 bg-white rounded-xl border border-gray-200 shadow-lg overflow-hidden z-50 max-h-[400px] overflow-y-auto">

                    <template x-if="loading">
                        <div class="p-5 text-center">
                            <div class="animate-spin inline-block w-4 h-4 border-2 border-t-transparent rounded-full"
                                style="border-color: var(--gold); border-top-color: transparent;"></div>
                        </div>
                    </template>

                    <template x-if="!loading && results.length > 0">
                        <div>
                            <template x-for="product in results" :key="product.id">
                                <a :href="product.url"
                                    class="flex items-center gap-3 px-3 py-2.5 hover:bg-gray-50 transition-colors duration-150 border-b border-gray-100 last:border-0">
                                    <div class="w-10 h-10 bg-gray-100 rounded-lg overflow-hidden shrink-0">
                                        <template x-if="product.image">
                                            <img :src="product.image" :alt="product.name"
                                                class="w-full h-full object-cover">
                                        </template>
                                        <template x-if="!product.image">
                                            <div class="w-full h-full flex items-center justify-center"
                                                style="color: var(--text-tertiary);">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="1.5"
                                                        d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                                </svg>
                                            </div>
                                        </template>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-[13px] font-medium truncate" style="color: var(--text-primary);"
                                            x-text="product.name"></p>
                                        <p class="text-[12px] font-semibold mt-0.5" style="color: var(--gold);"
                                            x-text="'$' + parseFloat(product.price).toFixed(2)"></p>
                                    </div>
                                </a>
                            </template>

                            <a :href="'{{ route('products.index') }}?search=' + encodeURIComponent(query)"
                                class="block py-2.5 text-center text-[12px] font-semibold bg-gray-50 hover:bg-gray-100 transition-colors duration-150"
                                style="color: var(--gold);">
                                عرض كل النتائج (<span x-text="total"></span>)
                            </a>
                        </div>
                    </template>

                    <template x-if="!loading && results.length === 0 && query.length >= 2">
                        <div class="p-6 text-center">
                            <p class="text-[12px]" style="color: var(--text-tertiary);">لا توجد نتائج</p>
                        </div>
                    </template>
                </div>
            </div>

            <div class="flex items-center gap-1">

                <button type="button" onclick="openMobileSearch()" title="بحث"
                    class="lg:hidden flex items-center justify-center w-9 h-9 rounded-lg transition-colors duration-150"
                    style="color: var(--text-secondary);"
                    onmouseover="this.style.backgroundColor='var(--bg-tertiary)'; this.style.color='var(--text-primary)';"
                    onmouseout="this.style.backgroundColor='transparent'; this.style.color='var(--text-secondary)';">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </button>

                <button type="button" onclick="toggleDarkMode()" title="تبديل الوضع"
                    class="flex items-center justify-center w-9 h-9 rounded-lg transition-colors duration-150"
                    style="color: var(--text-secondary);"
                    onmouseover="this.style.backgroundColor='var(--bg-tertiary)'; this.style.color='var(--text-primary)';"
                    onmouseout="this.style.backgroundColor='transparent'; this.style.color='var(--text-secondary)';">
                    <svg id="darkIconMoon" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                    </svg>
                    <svg id="darkIconSun" class="w-4 h-4 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                </button>

                @auth
                    <div class="relative" x-data="{ open: false }" @click.away="open = false">
                        <button @click="open = !open; loadNotifications()"
                            class="relative flex items-center justify-center w-9 h-9 rounded-lg transition-colors duration-150"
                            style="color: var(--text-secondary);"
                            onmouseover="this.style.backgroundColor='var(--bg-tertiary)'; this.style.color='var(--text-primary)';"
                            onmouseout="this.style.backgroundColor='transparent'; this.style.color='var(--text-secondary)';">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                            </svg>
                            @php
                                $unreadCount = \App\Models\Notification::where('user_id', auth()->id())->whereNull('read_at')->count();
                            @endphp
                            <span id="notifBadge"
                                class="{{ $unreadCount > 0 ? '' : 'hidden' }} absolute top-1 right-1 min-w-[16px] h-[16px] px-1 text-white text-[9px] font-bold rounded-full flex items-center justify-center"
                                style="background-color: #dc2626;">
                                {{ $unreadCount > 9 ? '9+' : $unreadCount }}
                            </span>
                        </button>

                        <div x-show="open" x-cloak x-transition:enter="transition ease-out duration-150"
                            x-transition:enter-start="opacity-0 -translate-y-1"
                            x-transition:enter-end="opacity-100 translate-y-0"
                            class="absolute left-0 mt-2 w-80 bg-white rounded-xl border border-gray-200 shadow-lg overflow-hidden z-50">
                            <div class="px-4 py-3 border-b border-gray-200 flex items-center justify-between">
                                <h3 class="text-[13px] font-semibold" style="color: var(--text-primary);">الإشعارات</h3>
                                <a href="{{ route('notifications.index') }}" class="text-[11px] font-medium"
                                    style="color: var(--gold);">عرض الكل</a>
                            </div>

                            <div id="notifList" class="max-h-96 overflow-y-auto">
                                <div class="p-6 text-center">
                                    <div class="animate-spin inline-block w-4 h-4 border-2 border-t-transparent rounded-full"
                                        style="border-color: var(--gold); border-top-color: transparent;"></div>
                                </div>
                            </div>

                            <div class="px-3 py-2 border-t border-gray-200 bg-gray-50">
                                <form action="{{ route('notifications.markAllRead') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="w-full text-center text-[11px] font-semibold py-1"
                                        style="color: var(--gold);">
                                        تعليم الكل كمقروء
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <a href="{{ route('cart.index') }}"
                        class="relative flex items-center justify-center w-9 h-9 rounded-lg transition-colors duration-150"
                        style="color: var(--text-secondary);"
                        onmouseover="this.style.backgroundColor='var(--bg-tertiary)'; this.style.color='var(--text-primary)';"
                        onmouseout="this.style.backgroundColor='transparent'; this.style.color='var(--text-secondary)';">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                        @if(\App\Services\CartService::count() > 0)
                            <span
                                class="absolute top-1 right-1 min-w-[16px] h-[16px] px-1 text-white text-[9px] font-bold rounded-full flex items-center justify-center"
                                style="background-color: var(--gold);">
                                {{ \App\Services\CartService::count() }}
                            </span>
                        @endif
                    </a>

                    <div class="relative" x-data="{ open: false }" @click.away="open = false">
                        <button @click="open = !open"
                            class="flex items-center justify-center w-9 h-9 rounded-lg transition-colors duration-150"
                            onmouseover="this.style.backgroundColor='var(--bg-tertiary)';"
                            onmouseout="this.style.backgroundColor='transparent';">
                            @if(auth()->user()->avatar)
                                <img src="{{ asset('storage/' . auth()->user()->avatar) }}" alt="{{ auth()->user()->name }}"
                                    class="w-7 h-7 rounded-full object-cover">
                            @else
                                <div class="w-7 h-7 rounded-full flex items-center justify-center text-white text-[11px] font-semibold"
                                    style="background-color: var(--gold);">
                                    {{ auth()->user()->initial }}
                                </div>
                            @endif
                        </button>

                        <div x-show="open" x-cloak x-transition:enter="transition ease-out duration-150"
                            x-transition:enter-start="opacity-0 -translate-y-1"
                            x-transition:enter-end="opacity-100 translate-y-0"
                            class="absolute left-0 mt-2 w-56 bg-white rounded-xl border border-gray-200 shadow-lg py-1 z-50">

                            @if(auth()->user()->role === 'admin' || auth()->user()->role === 'manager')
                                <a href="/admin"
                                    class="flex items-center gap-2.5 px-3 py-2 text-[13px] font-semibold hover:bg-gray-50 transition-colors duration-150"
                                    style="color: var(--gold);">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                    لوحة التحكم
                                </a>
                                <div class="border-t border-gray-100 my-1"></div>
                            @endif

                            <a href="{{ route('dashboard') }}"
                                class="block px-3 py-2 text-[13px] hover:bg-gray-50 transition-colors duration-150"
                                style="color: var(--text-primary);">
                                حسابي
                            </a>

                            <a href="{{ route('orders.index') }}"
                                class="block px-3 py-2 text-[13px] hover:bg-gray-50 transition-colors duration-150"
                                style="color: var(--text-primary);">
                                طلباتي
                            </a>

                            <a href="{{ route('wishlist.index') }}"
                                class="block px-3 py-2 text-[13px] hover:bg-gray-50 transition-colors duration-150"
                                style="color: var(--text-primary);">
                                المفضلة
                            </a>

                            <a href="{{ route('notifications.index') }}"
                                class="flex items-center justify-between px-3 py-2 text-[13px] hover:bg-gray-50 transition-colors duration-150"
                                style="color: var(--text-primary);">
                                <span>الإشعارات</span>
                                @php
                                    $navUnread = \App\Models\Notification::where('user_id', auth()->id())->whereNull('read_at')->count();
                                @endphp
                                @if($navUnread > 0)
                                    <span
                                        class="text-white text-[9px] font-bold rounded-full min-w-[16px] h-[16px] px-1 flex items-center justify-center"
                                        style="background-color: #dc2626;">
                                        {{ $navUnread > 9 ? '9+' : $navUnread }}
                                    </span>
                                @endif
                            </a>

                            <div class="border-t border-gray-100 my-1"></div>

                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit"
                                    class="block w-full text-right px-3 py-2 text-[13px] text-red-600 hover:bg-red-50 transition-colors duration-150">
                                    تسجيل خروج
                                </button>
                            </form>
                        </div>
                    </div>
                @else
                    <a href="{{ route('login') }}"
                        class="px-3 py-2 text-[13px] font-medium rounded-lg transition-colors duration-150"
                        style="color: var(--text-secondary);"
                        onmouseover="this.style.backgroundColor='var(--bg-tertiary)'; this.style.color='var(--text-primary)';"
                        onmouseout="this.style.backgroundColor='transparent'; this.style.color='var(--text-secondary)';">
                        دخول
                    </a>
                    <a href="{{ route('register') }}"
                        class="hidden sm:inline-block px-4 py-2 text-[13px] font-semibold rounded-lg text-white transition-all duration-150 hover:opacity-90"
                        style="background-color: var(--gold);">
                        تسجيل جديد
                    </a>
                @endauth

                <button type="button" onclick="document.getElementById('mobileMenu').classList.toggle('hidden')"
                    class="lg:hidden flex items-center justify-center w-9 h-9 rounded-lg transition-colors duration-150"
                    style="color: var(--text-secondary);">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
            </div>
        </div>

        <div id="mobileMenu" class="hidden lg:hidden pb-4 border-t border-gray-200 pt-4">
            <nav class="flex flex-col gap-1">
                <a href="{{ route('home') }}"
                    class="px-3 py-2 text-[13px] font-medium rounded-lg hover:bg-gray-50 transition-colors duration-150"
                    style="color: var(--text-primary);">الرئيسية</a>
                <a href="{{ route('products.index') }}"
                    class="px-3 py-2 text-[13px] font-medium rounded-lg hover:bg-gray-50 transition-colors duration-150"
                    style="color: var(--text-primary);">المنتجات</a>
                <a href="{{ route('products.index', ['has_discount' => 1]) }}"
                    class="px-3 py-2 text-[13px] font-medium rounded-lg hover:bg-gray-50 transition-colors duration-150"
                    style="color: var(--text-primary);">عروض</a>
                <a href="{{ route('pages.contact') }}"
                    class="px-3 py-2 text-[13px] font-medium rounded-lg hover:bg-gray-50 transition-colors duration-150"
                    style="color: var(--text-primary);">اتصل بنا</a>
            </nav>
        </div>
    </div>
</header>

<div id="mobileSearchOverlay" class="hidden fixed inset-0 bg-white dark:bg-zinc-900 z-[60]">
    <div class="max-w-2xl mx-auto px-4 pt-4" x-data="{ 
            query: '', 
            results: [], 
            total: 0,
            loading: false,
            async search() {
                if (this.query.length < 2) {
                    this.results = [];
                    return;
                }
                this.loading = true;
                try {
                    const res = await fetch(`{{ route('products.search') }}?q=${encodeURIComponent(this.query)}`);
                    const data = await res.json();
                    this.results = data.products;
                    this.total = data.total;
                } catch (e) {
                    console.error(e);
                }
                this.loading = false;
            }
        }">

        <div class="flex items-center gap-2 mb-4">
            <button type="button" onclick="closeMobileSearch()"
                class="flex items-center justify-center w-9 h-9 rounded-lg hover:bg-gray-100 transition-colors"
                style="color: var(--text-secondary);">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>

            <form action="{{ route('products.index') }}" method="GET" class="flex-1">
                <div class="relative">
                    <input type="text" name="search" x-model="query" x-ref="mobileSearchInput"
                        @input.debounce.300ms="search()" placeholder="ابحث عن منتج..." autocomplete="off"
                        class="w-full h-10 bg-gray-100 border-0 rounded-lg pr-10 pl-3 text-[14px] focus:outline-none focus:ring-2 focus:bg-white transition-all duration-150"
                        style="--tw-ring-color: var(--gold);">
                    <svg class="w-4 h-4 absolute top-1/2 -translate-y-1/2 right-3 pointer-events-none"
                        style="color: var(--text-tertiary);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
            </form>
        </div>

        <template x-if="loading">
            <div class="py-8 text-center">
                <div class="animate-spin inline-block w-5 h-5 border-2 border-t-transparent rounded-full"
                    style="border-color: var(--gold); border-top-color: transparent;"></div>
            </div>
        </template>

        <template x-if="!loading && results.length > 0">
            <div
                class="bg-white dark:bg-zinc-800 rounded-xl border border-gray-200 dark:border-zinc-700 overflow-hidden">
                <template x-for="product in results" :key="product.id">
                    <a :href="product.url"
                        class="flex items-center gap-3 px-3 py-3 hover:bg-gray-50 dark:hover:bg-zinc-700 transition-colors border-b border-gray-100 dark:border-zinc-700 last:border-0">
                        <div class="w-12 h-12 bg-gray-100 dark:bg-zinc-700 rounded-lg overflow-hidden shrink-0">
                            <template x-if="product.image">
                                <img :src="product.image" :alt="product.name" class="w-full h-full object-cover">
                            </template>
                            <template x-if="!product.image">
                                <div class="w-full h-full flex items-center justify-center"
                                    style="color: var(--text-tertiary);">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                            d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                    </svg>
                                </div>
                            </template>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-[13px] font-medium truncate" style="color: var(--text-primary);"
                                x-text="product.name"></p>
                            <p class="text-[12px] font-semibold mt-0.5" style="color: var(--gold);"
                                x-text="'$' + parseFloat(product.price).toFixed(2)"></p>
                        </div>
                    </a>
                </template>

                <a :href="'{{ route('products.index') }}?search=' + encodeURIComponent(query)"
                    class="block py-3 text-center text-[13px] font-semibold bg-gray-50 dark:bg-zinc-700 hover:bg-gray-100 dark:hover:bg-zinc-600 transition-colors"
                    style="color: var(--gold);">
                    عرض كل النتائج (<span x-text="total"></span>)
                </a>
            </div>
        </template>

        <template x-if="!loading && results.length === 0 && query.length >= 2">
            <div class="py-12 text-center">
                <p class="text-[13px]" style="color: var(--text-tertiary);">لا توجد نتائج لـ "<span
                        x-text="query"></span>"</p>
            </div>
        </template>

        <template x-if="query.length < 2">
            <div class="py-12 text-center">
                <p class="text-[13px]" style="color: var(--text-tertiary);">ابدأ بكتابة اسم المنتج للبحث</p>
            </div>
        </template>
    </div>
</div>

<script>
    function openMobileSearch() {
        const overlay = document.getElementById('mobileSearchOverlay');
        overlay.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
        setTimeout(() => {
            const input = overlay.querySelector('input[name="search"]');
            if (input) input.focus();
        }, 50);
    }

    function closeMobileSearch() {
        const overlay = document.getElementById('mobileSearchOverlay');
        overlay.classList.add('hidden');
        document.body.style.overflow = '';
    }

    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') {
            closeMobileSearch();
        }
    });
</script>