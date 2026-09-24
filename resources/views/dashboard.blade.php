@extends('layouts.app')

@section('title', 'حسابي | متجري')

@section('content')

<div class="container-x pt-12 lg:pt-16 pb-24">

    {{-- ════════════ WELCOME HEADER ════════════ --}}
    <header class="mb-12 lg:mb-16 pb-10 border-b border-stone-200 dark:border-stone-800">
        <span class="eyebrow block mb-4">— حسابي</span>
        <h1 class="display-2 mb-4 text-balance">
            مرحباً،
            <span class="text-forest dark:text-gold">{{ $user->name }}</span>
        </h1>
        <p class="text-base text-ink-muted dark:text-cream/60 text-pretty max-w-md">
            نظرة سريعة على نشاطك في المتجر.
        </p>
    </header>

    {{-- ════════════ STATS GRID ════════════ --}}
    @php
        $statCards = [
            [
                'route' => 'orders.index',
                'value' => $stats['total_orders'],
                'label' => 'إجمالي الطلبات',
                'icon'  => 'M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4',
                'color' => 'text-forest dark:text-gold',
                'bg'    => 'bg-forest/5 dark:bg-gold/10',
            ],
            [
                'route' => 'orders.index',
                'value' => $stats['pending_orders'],
                'label' => 'قيد المراجعة',
                'icon'  => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z',
                'color' => 'text-amber-600 dark:text-amber-400',
                'bg'    => 'bg-amber-50 dark:bg-amber-950/30',
            ],
            [
                'route' => 'orders.index',
                'value' => $stats['delivered_orders'],
                'label' => 'تم التوصيل',
                'icon'  => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z',
                'color' => 'text-green-600 dark:text-green-400',
                'bg'    => 'bg-green-50 dark:bg-green-950/30',
            ],
            [
                'route' => 'wishlist.index',
                'value' => $stats['wishlist_count'],
                'label' => 'في المفضلة',
                'icon'  => 'M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z',
                'color' => 'text-rose-600 dark:text-rose-400',
                'bg'    => 'bg-rose-50 dark:bg-rose-950/30',
            ],
        ];
    @endphp

    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-12">
        @foreach($statCards as $card)
            <a href="{{ route($card['route']) }}"
               class="group relative border border-stone-200 dark:border-stone-800 hover:border-forest dark:hover:border-gold p-6 transition-all duration-200 overflow-hidden"
               style="border-radius: 4px;">

                {{-- Icon --}}
                <div class="w-11 h-11 flex items-center justify-center mb-5 {{ $card['bg'] }} {{ $card['color'] }}" style="border-radius: 4px;">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="{{ $card['icon'] }}" />
                    </svg>
                </div>

                {{-- Value --}}
                <p class="font-display text-4xl font-bold tracking-tight text-ink dark:text-cream mb-2">
                    {{ $card['value'] }}
                </p>

                {{-- Label --}}
                <p class="text-[10px] font-semibold tracking-widest uppercase text-ink-muted dark:text-cream/50">
                    {{ $card['label'] }}
                </p>

                {{-- Hover arrow --}}
                <svg class="absolute top-6 left-6 w-4 h-4 text-ink-faint dark:text-cream/30 opacity-0 group-hover:opacity-100 -translate-x-2 group-hover:translate-x-0 transition-all duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                </svg>
            </a>
        @endforeach
    </div>

    {{-- ════════════ MAIN GRID ════════════ --}}
    <div class="grid lg:grid-cols-12 gap-8">

        {{-- ─── QUICK MENU ─── --}}
        <aside class="lg:col-span-4">
            <div class="border border-stone-200 dark:border-stone-800" style="border-radius: 4px;">

                <div class="px-6 py-5 border-b border-stone-200 dark:border-stone-800">
                    <span class="text-[10px] font-semibold tracking-widest uppercase text-ink-muted dark:text-cream/60">
                        القائمة السريعة
                    </span>
                </div>

                @php
                    $unreadNotifCount = \App\Models\Notification::where('user_id', auth()->id())->whereNull('read_at')->count();

                    $menuItems = [
                        ['route' => 'orders.index',        'label' => 'طلباتي',        'icon' => 'M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4'],
                        ['route' => 'wishlist.index',      'label' => 'المفضلة',       'icon' => 'M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z'],
                        ['route' => 'notifications.index', 'label' => 'الإشعارات',     'icon' => 'M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9', 'badge' => $unreadNotifCount],
                        ['route' => 'profile.edit',        'label' => 'الملف الشخصي', 'icon' => 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z'],
                        ['route' => 'cart.index',          'label' => 'سلة المشتريات', 'icon' => 'M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z'],
                    ];
                @endphp

                <div class="p-3">
                    @foreach($menuItems as $item)
                        <a href="{{ route($item['route']) }}"
                           class="group flex items-center justify-between gap-4 px-4 py-3.5 hover:bg-stone-50 dark:hover:bg-zinc-900 transition-colors"
                           style="border-radius: 4px;">

                            <span class="flex items-center gap-4">
                                <span class="w-9 h-9 flex items-center justify-center bg-forest/5 dark:bg-gold/10 text-forest dark:text-gold group-hover:bg-forest group-hover:text-cream dark:group-hover:bg-gold dark:group-hover:text-ink transition-colors" style="border-radius: 4px;">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="{{ $item['icon'] }}" />
                                    </svg>
                                </span>
                                <span class="font-display text-sm font-bold text-ink dark:text-cream">
                                    {{ $item['label'] }}
                                </span>
                            </span>

                            <span class="flex items-center gap-3">
                                @if(!empty($item['badge']) && $item['badge'] > 0)
                                    <span class="inline-flex items-center justify-center min-w-[20px] h-5 px-1.5 text-[10px] font-bold text-white bg-red-600" style="border-radius: 2px;">
                                        {{ $item['badge'] > 9 ? '9+' : $item['badge'] }}
                                    </span>
                                @endif
                                <svg class="w-3.5 h-3.5 text-ink-faint dark:text-cream/30 group-hover:text-forest dark:group-hover:text-gold group-hover:-translate-x-1 transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                                </svg>
                            </span>
                        </a>
                    @endforeach
                </div>
            </div>
        </aside>

        {{-- ─── RECENT ORDERS ─── --}}
        <div class="lg:col-span-8">
            <div class="border border-stone-200 dark:border-stone-800" style="border-radius: 4px;">

                <div class="flex items-center justify-between px-6 py-5 border-b border-stone-200 dark:border-stone-800">
                    <span class="text-[10px] font-semibold tracking-widest uppercase text-ink-muted dark:text-cream/60">
                        آخر الطلبات
                    </span>
                    <a href="{{ route('orders.index') }}" class="link-arrow text-xs">
                        عرض الكل
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                        </svg>
                    </a>
                </div>

                @if($recentOrders->count())
                    @php
                        $statusMap = [
                            'pending'    => ['label' => 'قيد المراجعة', 'color' => 'text-amber-700 dark:text-amber-300', 'bg' => 'bg-amber-50 dark:bg-amber-950/30', 'border' => 'border-amber-200 dark:border-amber-900/50'],
                            'processing' => ['label' => 'قيد المعالجة', 'color' => 'text-blue-700 dark:text-blue-300', 'bg' => 'bg-blue-50 dark:bg-blue-950/30', 'border' => 'border-blue-200 dark:border-blue-900/50'],
                            'shipped'    => ['label' => 'تم الشحن',    'color' => 'text-violet-700 dark:text-violet-300', 'bg' => 'bg-violet-50 dark:bg-violet-950/30', 'border' => 'border-violet-200 dark:border-violet-900/50'],
                            'delivered'  => ['label' => 'تم التوصيل', 'color' => 'text-green-700 dark:text-green-300', 'bg' => 'bg-green-50 dark:bg-green-950/30', 'border' => 'border-green-200 dark:border-green-900/50'],
                            'cancelled'  => ['label' => 'ملغي',        'color' => 'text-red-700 dark:text-red-300', 'bg' => 'bg-red-50 dark:bg-red-950/30', 'border' => 'border-red-200 dark:border-red-900/50'],
                        ];
                    @endphp

                    <div class="divide-y divide-stone-200 dark:divide-stone-800">
                        @foreach($recentOrders as $order)
                            @php
                                $status = $statusMap[$order->status] ?? ['label' => $order->status, 'color' => 'text-ink-muted', 'bg' => 'bg-stone-100 dark:bg-stone-800', 'border' => 'border-stone-200 dark:border-stone-700'];
                            @endphp

                            <a href="{{ route('orders.show', $order->id) }}"
                               class="group flex items-center justify-between gap-4 p-5 hover:bg-stone-50 dark:hover:bg-zinc-900 transition-colors">

                                <div class="flex items-center gap-4 min-w-0">
                                    <span class="w-11 h-11 flex items-center justify-center shrink-0 bg-forest/5 dark:bg-gold/10 text-forest dark:text-gold group-hover:bg-forest group-hover:text-cream dark:group-hover:bg-gold dark:group-hover:text-ink transition-colors" style="border-radius: 4px;">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                        </svg>
                                    </span>
                                    <div class="min-w-0">
                                        <p class="font-display text-sm font-bold truncate text-ink dark:text-cream mb-0.5">
                                            {{ $order->order_number }}
                                        </p>
                                        <p class="text-xs text-ink-faint dark:text-cream/40">
                                            <span dir="ltr">{{ $order->created_at->format('Y-m-d') }}</span>
                                            <span class="mx-1.5 opacity-40">·</span>
                                            {{ $order->items->count() }} {{ $order->items->count() === 1 ? 'منتج' : 'منتجات' }}
                                        </p>
                                    </div>
                                </div>

                                <div class="flex items-center gap-4 shrink-0">
                                    <span class="hidden sm:inline-flex items-center px-3 py-1.5 text-[10px] font-bold tracking-widest uppercase border {{ $status['bg'] }} {{ $status['border'] }} {{ $status['color'] }}" style="border-radius: 2px;">
                                        {{ $status['label'] }}
                                    </span>
                                    <span class="font-display text-base font-bold text-forest dark:text-gold whitespace-nowrap">
                                        ${{ number_format($order->total_amount, 2) }}
                                    </span>
                                    <svg class="w-4 h-4 text-ink-faint dark:text-cream/30 group-hover:text-forest dark:group-hover:text-gold group-hover:-translate-x-1 transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                                    </svg>
                                </div>
                            </a>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-20">

                        <div class="w-20 h-20 mx-auto mb-6 flex items-center justify-center border border-stone-300 dark:border-stone-700" style="border-radius: 4px;">
                            <svg class="w-8 h-8 text-ink-faint dark:text-cream/30" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                            </svg>
                        </div>

                        <p class="font-display font-bold text-lg mb-2">لا توجد طلبات بعد</p>
                        <p class="text-sm text-ink-muted dark:text-cream/60 mb-8">ابدأ التسوق واكتشف منتجاتنا</p>

                        <a href="{{ route('products.index') }}" class="btn-solid group inline-flex">
                            ابدأ التسوق
                            <svg class="w-4 h-4 transition-transform group-hover:-translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                            </svg>
                        </a>
                    </div>
                @endif
            </div>
        </div>

    </div>

</div>

@endsection