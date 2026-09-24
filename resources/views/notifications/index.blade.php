@extends('layouts.app')

@section('title', 'الإشعارات | متجري')

@section('content')

    <style>
        .notif-page {
            --gold: #14b8a6;
            --gold-soft: rgba(20, 184, 166, 0.1);
            --border-light: #e6e6e4;
            --text-primary: #0f0f0f;
            --text-secondary: #6b6b5e;
            --text-tertiary: #9a9a8c;
            --bg-primary: #fafaf9;
            --bg-tertiary: #f5f5f4;
        }
        .dark .notif-page {
            --gold: #2dd4bf;
            --gold-soft: rgba(45, 212, 191, 0.15);
            --border-light: #262626;
            --text-primary: #f5f5f0;
            --text-secondary: #969690;
            --text-tertiary: #6e6e64;
            --bg-primary: #0a0a0a;
            --bg-tertiary: #141414;
        }
    </style>

    <div class="notif-page container-narrow pt-12 lg:pt-16 pb-24">

        {{-- ════════════ HEADER ════════════ --}}
        <header class="mb-12 lg:mb-16 pb-8 border-b border-stone-200 dark:border-stone-800">
            <nav class="flex items-center gap-3 text-[10px] font-medium uppercase tracking-[0.2em] text-ink-faint dark:text-cream/40 mb-8" aria-label="breadcrumb">
                <a href="{{ route('home') }}" class="hover:text-forest dark:hover:text-gold transition-colors">الرئيسية</a>
                <span class="opacity-40">/</span>
                <span class="text-ink dark:text-cream">الإشعارات</span>
            </nav>

            <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-6">
                <div>
                    <span class="eyebrow block mb-4">— التنبيهات</span>
                    <h1 class="display-2">
                        آخر
                        <span class="text-forest dark:text-gold">التحديثات</span>
                    </h1>
                    <p class="mt-4 text-base text-ink-muted dark:text-cream/60 text-pretty">
                        تابع كل ما يخص طلباتك في مكان واحد.
                    </p>
                </div>

                @if($notifications->count())
                    <div class="flex flex-wrap gap-2 shrink-0">
                        <form action="{{ route('notifications.markAllRead') }}" method="POST">
                            @csrf
                            <button type="submit"
                                    class="inline-flex items-center gap-2 h-10 px-4 font-display font-bold text-[10px] tracking-widest uppercase text-white bg-forest dark:bg-gold dark:text-ink hover:opacity-90 transition-all"
                                    style="border-radius: 4px;">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                </svg>
                                تعليم الكل كمقروء
                            </button>
                        </form>

                        <form action="{{ route('notifications.clearAll') }}" method="POST"
                              onsubmit="return confirm('هل أنت متأكد من حذف جميع الإشعارات؟')">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                    class="inline-flex items-center gap-2 h-10 px-4 font-display font-bold text-[10px] tracking-widest uppercase text-red-600 dark:text-red-400 border border-red-200 dark:border-red-900/50 hover:bg-red-50 dark:hover:bg-red-950/30 transition-colors"
                                    style="border-radius: 4px;">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                                حذف الكل
                            </button>
                        </form>
                    </div>
                @endif
            </div>

            {{-- Live unread banner --}}
            <div id="liveNotifCount" class="hidden mt-6 items-center gap-3 px-4 py-3 border border-red-200 dark:border-red-900/50 bg-red-50 dark:bg-red-950/20 text-red-800 dark:text-red-300" style="border-radius: 4px;">
                <span class="relative flex h-2 w-2 shrink-0">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-500 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-2 w-2 bg-red-500"></span>
                </span>
                <span id="liveNotifCountText" class="text-xs font-semibold">لديك إشعارات جديدة</span>
            </div>
        </header>

        @if(session('success'))
            <div class="px-5 py-4 mb-8 text-sm border border-green-200 dark:border-green-900/50 bg-green-50 dark:bg-green-950/20 text-green-800 dark:text-green-300 flex items-center gap-3" style="border-radius: 4px;">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                {{ session('success') }}
            </div>
        @endif

        @if($notifications->count())

            {{-- ════════════ LIST ════════════ --}}
            <div class="border border-stone-200 dark:border-stone-800 divide-y divide-stone-200 dark:divide-stone-800" style="border-radius: 4px;">

                @foreach($notifications as $notification)
                    @php
                        $isRead = $notification->isRead();
                        $colorMap = [
                            'green' => ['bg' => 'bg-green-50 dark:bg-green-950/30', 'text' => 'text-green-600 dark:text-green-400', 'border' => 'border-green-200 dark:border-green-900/50'],
                            'yellow' => ['bg' => 'bg-amber-50 dark:bg-amber-950/30', 'text' => 'text-amber-600 dark:text-amber-400', 'border' => 'border-amber-200 dark:border-amber-900/50'],
                            'red' => ['bg' => 'bg-red-50 dark:bg-red-950/30', 'text' => 'text-red-600 dark:text-red-400', 'border' => 'border-red-200 dark:border-red-900/50'],
                            'blue' => ['bg' => 'bg-blue-50 dark:bg-blue-950/30', 'text' => 'text-blue-600 dark:text-blue-400', 'border' => 'border-blue-200 dark:border-blue-900/50'],
                        ];
                        $c = $colorMap[$notification->color] ?? $colorMap['blue'];

                        $iconPaths = [
                            'order' => 'M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4',
                            'shipping' => 'M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0',
                            'delivered' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z',
                            'cancelled' => 'M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z',
                            'payment' => 'M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z',
                            'promo' => 'M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z',
                        ];
                        $iconPath = $iconPaths[$notification->icon] ?? 'M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9';
                    @endphp

                    <div class="relative p-6 transition-colors {{ !$isRead ? 'bg-forest/[0.02] dark:bg-gold/[0.02]' : '' }}">

                        {{-- Unread indicator strip --}}
                        @if(!$isRead)
                            <span class="absolute top-0 bottom-0 right-0 w-1 bg-forest dark:bg-gold" style="border-radius: 0 4px 4px 0;"></span>
                        @endif

                        <div class="flex items-start gap-5">

                            {{-- Icon --}}
                            <div class="w-11 h-11 flex items-center justify-center shrink-0 border {{ $c['bg'] }} {{ $c['border'] }} {{ $c['text'] }}" style="border-radius: 4px;">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="{{ $iconPath }}" />
                                </svg>
                            </div>

                            {{-- Content --}}
                            <div class="flex-1 min-w-0">
                                <div class="flex items-start justify-between gap-4 mb-2 flex-wrap">
                                    <h3 class="font-display font-bold text-base flex items-center gap-2.5">
                                        @if(!$isRead)
                                            <span class="w-1.5 h-1.5 rounded-full bg-forest dark:bg-gold shrink-0"></span>
                                        @endif
                                        {{ $notification->title }}
                                    </h3>
                                    <span class="text-xs whitespace-nowrap text-ink-faint dark:text-cream/40">
                                        {{ $notification->created_at->diffForHumans() }}
                                    </span>
                                </div>

                                <p class="text-sm leading-relaxed text-ink-muted dark:text-cream/70 mb-4">
                                    {{ $notification->message }}
                                </p>

                                {{-- Actions --}}
                                <div class="flex items-center gap-5">
                                    @if($notification->link)
                                        <a href="{{ route('notifications.read', $notification->id) }}"
                                           class="group inline-flex items-center gap-1.5 text-xs font-semibold text-forest dark:text-gold hover:opacity-70 transition-opacity">
                                            عرض التفاصيل
                                            <svg class="w-3 h-3 transition-transform group-hover:-translate-x-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                                            </svg>
                                        </a>
                                    @elseif(!$isRead)
                                        <a href="{{ route('notifications.read', $notification->id) }}"
                                           class="text-xs font-semibold text-ink-muted dark:text-cream/50 hover:text-forest dark:hover:text-gold transition-colors">
                                            تعليم كمقروء
                                        </a>
                                    @endif

                                    <form action="{{ route('notifications.destroy', $notification->id) }}" method="POST"
                                          onsubmit="return confirm('حذف هذا الإشعار؟')" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="inline-flex items-center gap-1.5 text-xs font-medium text-ink-faint dark:text-cream/40 hover:text-red-600 dark:hover:text-red-400 transition-colors">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                            حذف
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Pagination --}}
            <div class="mt-12 pt-8 border-t border-stone-200 dark:border-stone-800 flex justify-center">
                {{ $notifications->links() }}
            </div>

        @else
            {{-- ════════════ EMPTY STATE ════════════ --}}
            <div class="text-center py-20 max-w-lg mx-auto">

                <div class="w-24 h-24 mx-auto mb-8 flex items-center justify-center border border-stone-300 dark:border-stone-700" style="border-radius: 4px;">
                    <svg class="w-10 h-10 text-ink-faint dark:text-cream/30" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                    </svg>
                </div>

                <span class="eyebrow block mb-4">— صندوق الإشعارات</span>

                <h2 class="display-2 mb-4">
                    لا توجد
                    <span class="text-forest dark:text-gold">إشعارات</span>
                </h2>

                <p class="text-base text-ink-muted dark:text-cream/60 mb-10 text-pretty">
                    ستظهر هنا الإشعارات المتعلقة بطلباتك أولاً بأول.
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

    <script>
        async function checkForNewNotifications() {
            try {
                const response = await fetch('{{ route('notifications.unread') }}', {
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    },
                });
                const data = await response.json();

                const banner = document.getElementById('liveNotifCount');
                const text = document.getElementById('liveNotifCountText');

                if (data.count > 0 && banner) {
                    banner.classList.remove('hidden');
                    banner.classList.add('inline-flex');
                    text.textContent = 'لديك ' + data.count + ' إشعار' + (data.count > 1 ? 'ات' : '') + ' جديد' + (data.count > 1 ? 'ة' : '');
                } else if (banner) {
                    banner.classList.add('hidden');
                    banner.classList.remove('inline-flex');
                }
            } catch (e) {
                console.error('Error checking notifications:', e);
            }
        }

        document.addEventListener('DOMContentLoaded', function () {
            checkForNewNotifications();
            const interval = setInterval(checkForNewNotifications, 10000);

            document.addEventListener('visibilitychange', function () {
                if (document.hidden) {
                    clearInterval(interval);
                } else {
                    checkForNewNotifications();
                }
            });
        });
    </script>

@endsection