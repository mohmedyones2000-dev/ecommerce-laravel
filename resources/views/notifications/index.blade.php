@extends('layouts.app')

@section('title', 'الإشعارات | متجري')

@section('content')

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

        <div class="flex flex-wrap items-start justify-between gap-4 mb-8">
            <div>
                <h1 class="text-2xl md:text-3xl font-bold mb-1.5" style="color: var(--text-primary);">الإشعارات</h1>
                <p class="text-[13px]" style="color: var(--text-secondary);">تابع آخر التحديثات على طلباتك</p>

                <div id="liveNotifCount" class="hidden mt-3 items-center gap-2 px-3 py-1.5 rounded-lg text-[12px] font-semibold"
                     style="background-color: #fef2f2; color: #991b1b; border: 1px solid #fecaca;">
                    <span class="relative flex h-2 w-2">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full opacity-75" style="background-color: #dc2626;"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2" style="background-color: #dc2626;"></span>
                    </span>
                    <span id="liveNotifCountText">لديك إشعارات جديدة</span>
                </div>
            </div>

            @if($notifications->count())
                <div class="flex gap-2">
                    <form action="{{ route('notifications.markAllRead') }}" method="POST">
                        @csrf
                        <button type="submit"
                                class="inline-flex items-center gap-1.5 h-9 px-4 rounded-lg font-semibold text-[12px] text-white transition-all duration-200 hover:opacity-90"
                                style="background-color: var(--gold);">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                            </svg>
                            تعليم الكل كمقروء
                        </button>
                    </form>

                    <form action="{{ route('notifications.clearAll') }}" method="POST"
                          onsubmit="return confirm('هل أنت متأكد من حذف جميع الإشعارات؟')">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                                class="inline-flex items-center gap-1.5 h-9 px-4 rounded-lg font-semibold text-[12px] transition-all duration-150"
                                style="background-color: #fee2e2; color: #dc2626;"
                                onmouseover="this.style.backgroundColor='#fecaca';"
                                onmouseout="this.style.backgroundColor='#fee2e2';">
                            حذف الكل
                        </button>
                    </form>
                </div>
            @endif
        </div>

        @if(session('success'))
            <div class="px-4 py-3 rounded-lg mb-5 text-[13px]" style="background-color: #f0fdf4; color: #166534; border: 1px solid #bbf7d0;">
                {{ session('success') }}
            </div>
        @endif

        @if($notifications->count())
            <div class="space-y-3">
                @foreach($notifications as $notification)
                    @php
                        $isRead = $notification->isRead();
                        $colorMap = [
                            'green' => ['#d1fae5', '#047857'],
                            'yellow' => ['#fef3c7', '#b45309'],
                            'red' => ['#fee2e2', '#b91c1c'],
                            'blue' => ['#dbeafe', '#1d4ed8'],
                        ];
                        $c = $colorMap[$notification->color] ?? $colorMap['blue'];
                    @endphp

                    <div class="rounded-xl border transition-all duration-200 overflow-hidden"
                         style="background-color: var(--bg-primary); border-color: {{ $isRead ? 'var(--border-light)' : 'var(--gold)' }}; border-{{ $isRead ? 'width' : 'right-width' }}: {{ $isRead ? '1px' : '3px' }};">

                        <div class="p-5">
                            <div class="flex items-start gap-4">

                                <div class="w-10 h-10 rounded-lg flex items-center justify-center shrink-0"
                                     style="background-color: {{ $c[0] }}; color: {{ $c[1] }};">
                                    @if($notification->icon === 'order')
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                        </svg>
                                    @elseif($notification->icon === 'shipping')
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0" />
                                        </svg>
                                    @elseif($notification->icon === 'delivered')
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    @elseif($notification->icon === 'cancelled')
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    @elseif($notification->icon === 'payment')
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                                        </svg>
                                    @elseif($notification->icon === 'promo')
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                                        </svg>
                                    @else
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                                        </svg>
                                    @endif
                                </div>

                                <div class="flex-1 min-w-0">
                                    <div class="flex items-start justify-between gap-3 mb-1.5">
                                        <h3 class="text-[13px] font-semibold flex items-center gap-2" style="color: var(--text-primary);">
                                            @if(!$isRead)
                                                <span class="w-1.5 h-1.5 rounded-full shrink-0" style="background-color: var(--gold);"></span>
                                            @endif
                                            {{ $notification->title }}
                                        </h3>
                                        <span class="text-[11px] whitespace-nowrap shrink-0" style="color: var(--text-tertiary);">
                                            {{ $notification->created_at->diffForHumans() }}
                                        </span>
                                    </div>

                                    <p class="text-[12px] leading-relaxed mb-3" style="color: var(--text-secondary);">
                                        {{ $notification->message }}
                                    </p>

                                    <div class="flex items-center gap-4">
                                        @if($notification->link)
                                            <a href="{{ route('notifications.read', $notification->id) }}"
                                               class="inline-flex items-center gap-1 text-[12px] font-semibold transition-colors duration-150"
                                               style="color: var(--gold);"
                                               onmouseover="this.style.opacity='0.7';"
                                               onmouseout="this.style.opacity='1';">
                                                عرض التفاصيل
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                                                </svg>
                                            </a>
                                        @elseif(!$isRead)
                                            <a href="{{ route('notifications.read', $notification->id) }}"
                                               class="text-[12px] font-medium transition-colors duration-150"
                                               style="color: var(--text-secondary);"
                                               onmouseover="this.style.color='var(--gold)';"
                                               onmouseout="this.style.color='var(--text-secondary)';">
                                                تعليم كمقروء
                                            </a>
                                        @endif

                                        <form action="{{ route('notifications.destroy', $notification->id) }}" method="POST"
                                              onsubmit="return confirm('حذف هذا الإشعار؟')" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="text-[11px] font-medium transition-colors duration-150"
                                                    style="color: var(--text-tertiary);"
                                                    onmouseover="this.style.color='#dc2626';"
                                                    onmouseout="this.style.color='var(--text-tertiary)';">
                                                حذف
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-6 flex justify-center">
                {{ $notifications->links() }}
            </div>
        @else
            <div class="rounded-xl border p-14 text-center max-w-lg mx-auto" style="background-color: var(--bg-primary); border-color: var(--border-light);">
                <div class="w-16 h-16 rounded-xl flex items-center justify-center mx-auto mb-5" style="background-color: var(--bg-tertiary);">
                    <svg class="w-8 h-8" style="color: var(--text-tertiary);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                    </svg>
                </div>
                <h2 class="text-lg font-bold mb-2" style="color: var(--text-primary);">لا توجد إشعارات</h2>
                <p class="text-[13px] mb-6" style="color: var(--text-secondary);">ستظهر هنا الإشعارات المتعلقة بطلباتك.</p>
                <a href="{{ route('products.index') }}"
                   class="inline-flex items-center gap-2 h-11 px-6 rounded-lg font-semibold text-[13px] text-white transition-all duration-200 hover:opacity-90"
                   style="background-color: var(--gold);">
                    تصفح المنتجات
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
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