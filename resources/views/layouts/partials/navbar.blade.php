@php $settings = \App\Models\SiteSetting::current(); @endphp

<header x-data="{ mobileOpen: false }" class="sticky top-0 z-50 border-b border-gray-200 dark:border-zinc-800 bg-canvas/85 backdrop-blur-xl">
    <div class="container-x">
        <div class="flex items-center justify-between h-20 gap-6">

            <a href="{{ route('home') }}" class="flex items-center gap-3 shrink-0">
                @if($settings->logo)
                    <img src="{{ asset('storage/' . $settings->logo) }}" alt="{{ $settings->site_name }}" class="w-10 h-10 object-cover" style="border-radius: 4px;">
                @else
                    <div class="w-10 h-10 text-white flex items-center justify-center font-bold" style="border-radius: 4px; background: linear-gradient(135deg, #14b8a6, #005a96);">
                        {{ mb_substr($settings->site_name ?? 'م', 0, 1, 'UTF-8') }}
                    </div>
                @endif
                <span class="font-bold text-xl tracking-tight">{{ $settings->site_name ?? 'متجري' }}</span>
            </a>

            <nav class="hidden lg:flex items-center gap-10">
                @foreach([['route' => 'home', 'label' => 'الرئيسية'], ['route' => 'products.index', 'label' => 'المتجر'], ['route' => 'pages.contact', 'label' => 'اتصل بنا']] as $link)
                    @php $active = request()->routeIs($link['route']); @endphp
                    <a href="{{ route($link['route']) }}" class="relative text-sm font-medium py-1 transition-colors {{ $active ? 'text-ink dark:text-cream' : 'text-ink-muted dark:text-cream/60 hover:text-ink dark:hover:text-cream' }}">
                        {{ $link['label'] }}
                        @if($active)
                            <span class="absolute -bottom-0.5 right-0 left-0 h-px" style="background-color: #14b8a6;"></span>
                        @endif
                    </a>
                @endforeach
            </nav>

            <div class="flex items-center gap-1">
                <button type="button" onclick="openMobileSearch()" class="flex items-center justify-center w-10 h-10 text-ink-muted dark:text-cream/60 hover:text-ink dark:hover:text-cream transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </button>

                <button type="button" onclick="toggleDarkMode()" class="flex items-center justify-center w-10 h-10 text-ink-muted dark:text-cream/60 hover:text-ink dark:hover:text-cream transition-colors">
                    <svg id="darkIconMoon" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/></svg>
                    <svg id="darkIconSun" class="w-5 h-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                </button>

                @auth
                    <div class="relative" x-data="{ open: false }" @click.away="open = false">
                        <button @click="open = !open; loadNotifications()" class="relative flex items-center justify-center w-10 h-10 text-ink-muted dark:text-cream/60 hover:text-ink dark:hover:text-cream transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                            @php $unread = \App\Models\Notification::where('user_id', auth()->id())->whereNull('read_at')->count(); @endphp
                            <span id="notifBadge" class="{{ $unread > 0 ? '' : 'hidden' }} absolute top-1.5 right-1.5 min-w-[16px] h-4 px-1 text-white text-[9px] font-bold flex items-center justify-center" style="border-radius: 2px; background-color: #14b8a6;">{{ $unread > 9 ? '9+' : $unread }}</span>
                        </button>
                        <div x-show="open" x-cloak x-transition.opacity.duration.200ms class="absolute left-0 top-full mt-3 w-80 bg-white dark:bg-zinc-900 border border-gray-200 dark:border-zinc-800 shadow-2xl overflow-hidden" style="border-radius: 4px;">
                            <div class="px-4 py-3 border-b border-gray-200 dark:border-zinc-800 flex items-center justify-between">
                                <h3 class="text-sm font-bold">الإشعارات</h3>
                                <a href="{{ route('notifications.index') }}" class="text-xs font-medium" style="color: #005a96;">عرض الكل</a>
                            </div>
                            <div id="notifList" class="max-h-80 overflow-y-auto"></div>
                        </div>
                    </div>

                    <a href="{{ route('cart.index') }}" class="relative flex items-center justify-center w-10 h-10 text-ink-muted dark:text-cream/60 hover:text-ink dark:hover:text-cream transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                        @if(\App\Services\CartService::count() > 0)
                            <span class="absolute top-1.5 right-1.5 min-w-[16px] h-4 px-1 text-white text-[9px] font-bold flex items-center justify-center" style="border-radius: 2px; background-color: #14b8a6;">{{ \App\Services\CartService::count() }}</span>
                        @endif
                    </a>

                    <div class="relative" x-data="{ open: false }" @click.away="open = false">
                        <button @click="open = !open" class="flex items-center justify-center w-10 h-10">
                            @if(auth()->user()->avatar)
                                <img src="{{ asset('storage/' . auth()->user()->avatar) }}" class="w-9 h-9 object-cover" style="border-radius: 4px;">
                            @else
                                <div class="w-9 h-9 text-white flex items-center justify-center text-xs font-bold" style="border-radius: 4px; background: linear-gradient(135deg, #14b8a6, #005a96);">{{ auth()->user()->initial }}</div>
                            @endif
                        </button>
                        <div x-show="open" x-cloak class="absolute left-0 top-full mt-3 w-56 bg-white dark:bg-zinc-900 border border-gray-200 dark:border-zinc-800 shadow-2xl py-2 z-50" style="border-radius: 4px;">
                            <div class="px-4 py-3 border-b border-gray-200 dark:border-zinc-800">
                                <p class="text-sm font-bold truncate">{{ auth()->user()->name }}</p>
                                <p class="text-xs opacity-60 truncate">{{ auth()->user()->email }}</p>
                            </div>
                            @if(in_array(auth()->user()->role, ['admin', 'manager']))
                                <a href="/admin" class="flex items-center gap-3 px-4 py-2.5 text-sm font-medium hover:bg-gray-50 dark:hover:bg-zinc-800" style="color: #005a96;">لوحة التحكم</a>
                            @endif
                            <a href="{{ route('dashboard') }}" class="block px-4 py-2.5 text-sm hover:bg-gray-50 dark:hover:bg-zinc-800">حسابي</a>
                            <a href="{{ route('orders.index') }}" class="block px-4 py-2.5 text-sm hover:bg-gray-50 dark:hover:bg-zinc-800">طلباتي</a>
                            <a href="{{ route('wishlist.index') }}" class="block px-4 py-2.5 text-sm hover:bg-gray-50 dark:hover:bg-zinc-800">المفضلة</a>
                            <div class="border-t border-gray-200 dark:border-zinc-800 mt-2 pt-2">
                                <form method="POST" action="{{ route('logout') }}">@csrf
                                    <button type="submit" class="block w-full text-right px-4 py-2.5 text-sm font-medium text-red-600 hover:bg-red-50 dark:hover:bg-red-950/30">تسجيل خروج</button>
                                </form>
                            </div>
                        </div>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="hidden sm:inline-block text-sm font-medium text-ink-muted dark:text-cream/60 hover:text-ink dark:hover:text-cream px-3">دخول</a>
                    <a href="{{ route('register') }}" class="hidden sm:inline-block btn-brand text-xs px-5 py-2.5">حساب جديد</a>
                @endauth

                <button type="button" @click="mobileOpen = !mobileOpen" class="lg:hidden flex items-center justify-center w-10 h-10">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/></svg>
                </button>
            </div>
        </div>

        <div x-show="mobileOpen" x-cloak class="lg:hidden border-t border-gray-200 dark:border-zinc-800 py-6">
            <nav class="flex flex-col gap-4">
                <a href="{{ route('home') }}" class="text-2xl font-bold">الرئيسية</a>
                <a href="{{ route('products.index') }}" class="text-2xl font-bold">المتجر</a>
                <a href="{{ route('products.index', ['has_discount' => 1]) }}" class="text-2xl font-bold">عروض</a>
                <a href="{{ route('pages.contact') }}" class="text-2xl font-bold">اتصل بنا</a>
            </nav>
        </div>
    </div>
</header>

<div id="searchOverlay" class="hidden fixed inset-0 bg-canvas z-[70]" x-data="{ query: '', results: [], total: 0, loading: false, async search() { if (this.query.length < 2) { this.results = []; return; } this.loading = true; try { const res = await fetch('{{ route('products.search') }}?q=' + encodeURIComponent(this.query)); const data = await res.json(); this.results = data.products; this.total = data.total; } catch (e) {} this.loading = false; } }">
    <div class="container-narrow pt-8 md:pt-16">
        <div class="flex items-center justify-between mb-10">
            <span class="eyebrow">بحث</span>
            <button onclick="closeSearch()" class="w-10 h-10 flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <form action="{{ route('products.index') }}" method="GET" class="border-b-2 border-ink dark:border-cream pb-4 mb-8">
            <input type="text" name="search" x-model="query" x-ref="searchInput" @input.debounce.250ms="search()" placeholder="ابحث عن منتج..." autocomplete="off" class="w-full bg-transparent text-3xl md:text-5xl font-bold placeholder:opacity-30 focus:outline-none">
        </form>
        <template x-if="loading"><p class="text-center text-sm">جارٍ البحث...</p></template>
        <template x-if="!loading && results.length > 0">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                <template x-for="product in results" :key="product.id">
                    <a :href="product.url" class="group block">
                        <div class="aspect-square overflow-hidden bg-gray-100 dark:bg-zinc-800 mb-3" style="border-radius: 4px;">
                            <template x-if="product.image"><img :src="product.image" :alt="product.name" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"></template>
                        </div>
                        <p class="text-sm font-medium line-clamp-1" x-text="product.name"></p>
                        <p class="text-xs mt-1" style="color: #14b8a6;" x-text="'$' + parseFloat(product.price).toFixed(2)"></p>
                    </a>
                </template>
            </div>
        </template>
        <template x-if="!loading && results.length === 0 && query.length >= 2"><p class="text-center text-sm">لا توجد نتائج</p></template>
        <template x-if="query.length < 2"><p class="text-center text-sm">ابدأ بكتابة اسم المنتج</p></template>
    </div>
</div>

<script>
    function openMobileSearch() { document.getElementById('searchOverlay').classList.remove('hidden'); document.body.style.overflow = 'hidden'; setTimeout(() => document.querySelector('#searchOverlay [x-ref="searchInput"]')?.focus(), 50); }
    function closeSearch() { document.getElementById('searchOverlay').classList.add('hidden'); document.body.style.overflow = ''; }
    document.addEventListener('keydown', e => { if (e.key === 'Escape') closeSearch(); });
</script>