@extends('layouts.app')

@section('title', 'حسابي | متجري')

@section('content')

    <div class="max-w-[1400px] mx-auto px-4 sm:px-6 lg:px-8 py-8">

        <div class="rounded-xl p-6 md:p-8 mb-6" style="background-color: var(--gold-soft); border: 1px solid var(--border-light);">
            <h1 class="text-xl md:text-2xl font-bold mb-1" style="color: var(--text-primary);">
                مرحباً، {{ $user->name }}
            </h1>
            <p class="text-[13px]" style="color: var(--text-secondary);">نتمنى لك تجربة تسوق رائعة</p>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mb-6">

            <a href="{{ route('orders.index') }}"
               class="rounded-xl border p-4 transition-all duration-200 hover:-translate-y-0.5"
               style="background-color: var(--bg-primary); border-color: var(--border-light);"
               onmouseover="this.style.borderColor='var(--gold)';"
               onmouseout="this.style.borderColor='var(--border-light)';">
                <div class="w-10 h-10 rounded-lg flex items-center justify-center mb-3"
                     style="background-color: #dbeafe; color: #1d4ed8;">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                    </svg>
                </div>
                <p class="text-2xl font-bold mb-0.5" style="color: var(--text-primary);">{{ $stats['total_orders'] }}</p>
                <p class="text-[12px]" style="color: var(--text-secondary);">إجمالي الطلبات</p>
            </a>

            <a href="{{ route('orders.index') }}"
               class="rounded-xl border p-4 transition-all duration-200 hover:-translate-y-0.5"
               style="background-color: var(--bg-primary); border-color: var(--border-light);"
               onmouseover="this.style.borderColor='var(--gold)';"
               onmouseout="this.style.borderColor='var(--border-light)';">
                <div class="w-10 h-10 rounded-lg flex items-center justify-center mb-3"
                     style="background-color: #fef3c7; color: #b45309;">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <p class="text-2xl font-bold mb-0.5" style="color: var(--text-primary);">{{ $stats['pending_orders'] }}</p>
                <p class="text-[12px]" style="color: var(--text-secondary);">قيد المراجعة</p>
            </a>

            <a href="{{ route('orders.index') }}"
               class="rounded-xl border p-4 transition-all duration-200 hover:-translate-y-0.5"
               style="background-color: var(--bg-primary); border-color: var(--border-light);"
               onmouseover="this.style.borderColor='var(--gold)';"
               onmouseout="this.style.borderColor='var(--border-light)';">
                <div class="w-10 h-10 rounded-lg flex items-center justify-center mb-3"
                     style="background-color: #d1fae5; color: #047857;">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <p class="text-2xl font-bold mb-0.5" style="color: var(--text-primary);">{{ $stats['delivered_orders'] }}</p>
                <p class="text-[12px]" style="color: var(--text-secondary);">تم التوصيل</p>
            </a>

            <a href="{{ route('wishlist.index') }}"
               class="rounded-xl border p-4 transition-all duration-200 hover:-translate-y-0.5"
               style="background-color: var(--bg-primary); border-color: var(--border-light);"
               onmouseover="this.style.borderColor='var(--gold)';"
               onmouseout="this.style.borderColor='var(--border-light)';">
                <div class="w-10 h-10 rounded-lg flex items-center justify-center mb-3"
                     style="background-color: #fee2e2; color: #b91c1c;">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                    </svg>
                </div>
                <p class="text-2xl font-bold mb-0.5" style="color: var(--text-primary);">{{ $stats['wishlist_count'] }}</p>
                <p class="text-[12px]" style="color: var(--text-secondary);">في المفضلة</p>
            </a>
        </div>

        <div class="grid md:grid-cols-3 gap-5">

            <div class="md:col-span-1">
                <div class="rounded-xl border overflow-hidden" style="background-color: var(--bg-primary); border-color: var(--border-light);">
                    <div class="px-5 py-4 border-b" style="border-color: var(--border-light);">
                        <h2 class="text-[14px] font-semibold" style="color: var(--text-primary);">القائمة السريعة</h2>
                    </div>

                    @php
                        $unreadNotifCount = \App\Models\Notification::where('user_id', auth()->id())->whereNull('read_at')->count();
                    @endphp

                    <div class="p-2">
                        <a href="{{ route('orders.index') }}"
                           class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-colors duration-150"
                           onmouseover="this.style.backgroundColor='var(--bg-tertiary)';"
                           onmouseout="this.style.backgroundColor='transparent';">
                            <div class="w-8 h-8 rounded-lg flex items-center justify-center" style="background-color: var(--gold-soft); color: var(--gold);">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                </svg>
                            </div>
                            <span class="text-[13px] font-medium" style="color: var(--text-primary);">طلباتي</span>
                        </a>

                        <a href="{{ route('wishlist.index') }}"
                           class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-colors duration-150"
                           onmouseover="this.style.backgroundColor='var(--bg-tertiary)';"
                           onmouseout="this.style.backgroundColor='transparent';">
                            <div class="w-8 h-8 rounded-lg flex items-center justify-center" style="background-color: var(--gold-soft); color: var(--gold);">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                </svg>
                            </div>
                            <span class="text-[13px] font-medium" style="color: var(--text-primary);">المفضلة</span>
                        </a>

                        <a href="{{ route('notifications.index') }}"
                           class="flex items-center justify-between px-3 py-2.5 rounded-lg transition-colors duration-150"
                           onmouseover="this.style.backgroundColor='var(--bg-tertiary)';"
                           onmouseout="this.style.backgroundColor='transparent';">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-lg flex items-center justify-center" style="background-color: var(--gold-soft); color: var(--gold);">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                                    </svg>
                                </div>
                                <span class="text-[13px] font-medium" style="color: var(--text-primary);">الإشعارات</span>
                            </div>
                            @if($unreadNotifCount > 0)
                                <span class="text-white text-[10px] font-bold rounded-full min-w-[18px] h-[18px] px-1 flex items-center justify-center" style="background-color: #dc2626;">
                                    {{ $unreadNotifCount > 9 ? '9+' : $unreadNotifCount }}
                                </span>
                            @endif
                        </a>

                        <a href="{{ route('profile.edit') }}"
                           class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-colors duration-150"
                           onmouseover="this.style.backgroundColor='var(--bg-tertiary)';"
                           onmouseout="this.style.backgroundColor='transparent';">
                            <div class="w-8 h-8 rounded-lg flex items-center justify-center" style="background-color: var(--gold-soft); color: var(--gold);">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                            </div>
                            <span class="text-[13px] font-medium" style="color: var(--text-primary);">الملف الشخصي</span>
                        </a>

                        <a href="{{ route('cart.index') }}"
                           class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-colors duration-150"
                           onmouseover="this.style.backgroundColor='var(--bg-tertiary)';"
                           onmouseout="this.style.backgroundColor='transparent';">
                            <div class="w-8 h-8 rounded-lg flex items-center justify-center" style="background-color: var(--gold-soft); color: var(--gold);">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                            </div>
                            <span class="text-[13px] font-medium" style="color: var(--text-primary);">سلة المشتريات</span>
                        </a>
                    </div>
                </div>
            </div>

            <div class="md:col-span-2">
                <div class="rounded-xl border overflow-hidden" style="background-color: var(--bg-primary); border-color: var(--border-light);">
                    <div class="px-5 py-4 border-b flex items-center justify-between" style="border-color: var(--border-light);">
                        <h2 class="text-[14px] font-semibold" style="color: var(--text-primary);">آخر الطلبات</h2>
                        <a href="{{ route('orders.index') }}" class="text-[12px] font-semibold flex items-center gap-1"
                           style="color: var(--gold);">
                            عرض الكل
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                            </svg>
                        </a>
                    </div>

                    @if($recentOrders->count())
                        <div class="divide-y" style="border-color: var(--border-light);">
                            @foreach($recentOrders as $order)
                                @php
                                    $labels = [
                                        'pending' => 'قيد المراجعة',
                                        'processing' => 'قيد المعالجة',
                                        'shipped' => 'تم الشحن',
                                        'delivered' => 'تم التوصيل',
                                        'cancelled' => 'ملغي',
                                    ];
                                    $colors = [
                                        'pending' => ['#fef3c7', '#b45309'],
                                        'processing' => ['#dbeafe', '#1d4ed8'],
                                        'shipped' => ['#ede9fe', '#6d28d9'],
                                        'delivered' => ['#d1fae5', '#047857'],
                                        'cancelled' => ['#fee2e2', '#b91c1c'],
                                    ];
                                    $c = $colors[$order->status] ?? ['#e5e7eb', '#6b7280'];
                                @endphp

                                <a href="{{ route('orders.show', $order->id) }}"
                                   class="flex items-center justify-between p-4 transition-colors duration-150 gap-4"
                                   onmouseover="this.style.backgroundColor='var(--bg-tertiary)';"
                                   onmouseout="this.style.backgroundColor='transparent';">
                                    <div class="flex items-center gap-3 min-w-0">
                                        <div class="w-9 h-9 rounded-lg flex items-center justify-center shrink-0" style="background-color: var(--gold-soft); color: var(--gold);">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                            </svg>
                                        </div>
                                        <div class="min-w-0">
                                            <p class="text-[13px] font-semibold truncate" style="color: var(--text-primary);">{{ $order->order_number }}</p>
                                            <p class="text-[11px]" style="color: var(--text-tertiary);">{{ $order->created_at->format('Y-m-d') }} · {{ $order->items->count() }} منتج</p>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-3 shrink-0">
                                        <span class="text-[11px] font-semibold px-2 py-1 rounded"
                                              style="background-color: {{ $c[0] }}; color: {{ $c[1] }};">
                                            {{ $labels[$order->status] ?? $order->status }}
                                        </span>
                                        <p class="text-[13px] font-bold" style="color: var(--gold);">${{ number_format($order->total_amount, 2) }}</p>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    @else
                        <div class="py-14 text-center">
                            <div class="w-14 h-14 rounded-xl flex items-center justify-center mx-auto mb-4" style="background-color: var(--bg-tertiary);">
                                <svg class="w-7 h-7" style="color: var(--text-tertiary);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                                </svg>
                            </div>
                            <p class="text-[13px] mb-5" style="color: var(--text-secondary);">لا توجد طلبات بعد</p>
                            <a href="{{ route('products.index') }}"
                               class="inline-flex items-center gap-2 h-10 px-5 rounded-lg font-semibold text-[12px] text-white transition-all duration-200 hover:opacity-90"
                               style="background-color: var(--gold);">
                                ابدأ التسوق
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                                </svg>
                            </a>
                        </div>
                    @endif
                </div>
            </div>

        </div>

    </div>

@endsection