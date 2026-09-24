@extends('layouts.app')

@section('title', 'طلباتي | متجري')

@section('content')

    <div class="container-x pt-12 lg:pt-16 pb-24">

        {{-- ════════════ HEADER ════════════ --}}
        <header class="mb-12 lg:mb-16 pb-8 border-b border-stone-200 dark:border-stone-800">
            <nav class="flex items-center gap-3 text-[10px] font-medium uppercase tracking-[0.2em] text-ink-faint dark:text-cream/40 mb-8" aria-label="breadcrumb">
                <a href="{{ route('home') }}" class="hover:text-forest dark:hover:text-gold transition-colors">الرئيسية</a>
                <span class="opacity-40">/</span>
                <span class="text-ink dark:text-cream">طلباتي</span>
            </nav>

            <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-6">
                <div>
                    <span class="eyebrow block mb-4">— سجل الطلبات</span>
                    <h1 class="display-2 mb-4 text-balance">
                        طلباتي
                    </h1>
                    <p class="text-base text-ink-muted dark:text-cream/60 text-pretty max-w-md">
                        @if($orders->count())
                            تابع حالة طلباتك الحالية وسجل مشترياتك السابقة.
                        @else
                            كل طلباتك ستظهر هنا بعد أن تقوم بأول عملية شراء.
                        @endif
                    </p>
                </div>

                @if($orders->count())
                    <a href="{{ route('products.index') }}" class="link-arrow shrink-0">
                        تصفح المزيد
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                        </svg>
                    </a>
                @endif
            </div>
        </header>

        {{-- ════════════ SESSION ════════════ --}}
        @if(session('success'))
            <div class="mb-8 px-5 py-4 text-sm border border-green-200 dark:border-green-900/50 bg-green-50 dark:bg-green-950/20 text-green-800 dark:text-green-300 flex items-start gap-3" style="border-radius: 4px;">
                <svg class="w-5 h-5 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if($orders->count())

            {{-- ════════════ ORDERS LIST ════════════ --}}
            <div class="space-y-4">
                @foreach($orders as $order)
                    @php
                        $statusMap = [
                            'pending' => ['label' => 'قيد المراجعة', 'color' => 'text-amber-700 dark:text-amber-300', 'bg' => 'bg-amber-50 dark:bg-amber-950/30', 'border' => 'border-amber-200 dark:border-amber-900/50', 'bar' => '#f59e0b'],
                            'processing' => ['label' => 'قيد المعالجة', 'color' => 'text-blue-700 dark:text-blue-300', 'bg' => 'bg-blue-50 dark:bg-blue-950/30', 'border' => 'border-blue-200 dark:border-blue-900/50', 'bar' => '#3b82f6'],
                            'shipped' => ['label' => 'تم الشحن', 'color' => 'text-violet-700 dark:text-violet-300', 'bg' => 'bg-violet-50 dark:bg-violet-950/30', 'border' => 'border-violet-200 dark:border-violet-900/50', 'bar' => '#8b5cf6'],
                            'delivered' => ['label' => 'تم التوصيل', 'color' => 'text-green-700 dark:text-green-300', 'bg' => 'bg-green-50 dark:bg-green-950/30', 'border' => 'border-green-200 dark:border-green-900/50', 'bar' => '#10b981'],
                            'cancelled' => ['label' => 'ملغي', 'color' => 'text-red-700 dark:text-red-300', 'bg' => 'bg-red-50 dark:bg-red-950/30', 'border' => 'border-red-200 dark:border-red-900/50', 'bar' => '#ef4444'],
                        ];
                        $status = $statusMap[$order->status] ?? ['label' => $order->status, 'color' => 'text-ink-muted', 'bg' => 'bg-stone-100 dark:bg-stone-800', 'border' => 'border-stone-200 dark:border-stone-700', 'bar' => '#a1a1aa'];

                        $statusSteps = ['pending', 'processing', 'shipped', 'delivered'];
                        $currentStep = array_search($order->status, $statusSteps);
                        $progress = $order->status === 'cancelled' ? 0 : (($currentStep + 1) / count($statusSteps)) * 100;
                    @endphp

                    <a href="{{ route('orders.show', $order->id) }}"
                       class="group block border border-stone-200 dark:border-stone-800 hover:border-forest dark:hover:border-gold transition-all duration-200"
                       style="border-radius: 4px;">

                        <div class="p-6">

                            {{-- Top: Number + Status --}}
                            <div class="flex flex-wrap items-start justify-between gap-4 mb-6">
                                <div class="min-w-0">
                                    <p class="text-[10px] font-semibold tracking-widest uppercase text-ink-muted dark:text-cream/50 mb-2">
                                        رقم الطلب
                                    </p>
                                    <p class="font-display text-xl font-bold truncate text-ink dark:text-cream group-hover:text-forest dark:group-hover:text-gold transition-colors" dir="ltr">
                                        {{ $order->order_number }}
                                    </p>
                                </div>

                                <span class="inline-flex items-center gap-2 px-3 py-1.5 text-[10px] font-bold tracking-widest uppercase border {{ $status['bg'] }} {{ $status['border'] }} {{ $status['color'] }} shrink-0" style="border-radius: 2px;">
                                    <span class="w-1.5 h-1.5 rounded-full" style="background-color: {{ $status['bar'] }};"></span>
                                    {{ $status['label'] }}
                                </span>
                            </div>

                            {{-- Progress bar --}}
                            @if($order->status !== 'cancelled')
                                <div class="mb-6">
                                    <div class="h-1 bg-stone-100 dark:bg-stone-800 overflow-hidden" style="border-radius: 2px;">
                                        <div class="h-full transition-all duration-700" style="width: {{ $progress }}%; background-color: {{ $status['bar'] }}; border-radius: 2px;"></div>
                                    </div>
                                    <div class="flex justify-between mt-3 text-[10px] font-medium tracking-widest uppercase text-ink-faint dark:text-cream/30">
                                        <span>الاستلام</span>
                                        <span>المعالجة</span>
                                        <span>الشحن</span>
                                        <span>التوصيل</span>
                                    </div>
                                </div>
                            @endif

                            {{-- Bottom: meta + total + arrow --}}
                            <div class="flex flex-wrap items-center justify-between gap-4 pt-5 border-t border-stone-200 dark:border-stone-800">

                                <div class="flex items-center gap-3 text-xs text-ink-muted dark:text-cream/50">
                                    <span class="inline-flex items-center gap-2">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                        </svg>
                                        {{ $order->items_count }} {{ $order->items_count == 1 ? 'منتج' : 'منتجات' }}
                                    </span>
                                    <span class="opacity-40">·</span>
                                    <span dir="ltr">{{ $order->created_at->format('Y-m-d') }}</span>
                                </div>

                                <div class="flex items-center gap-4 shrink-0">
                                    <span class="font-display text-lg font-bold text-forest dark:text-gold">
                                        ${{ number_format($order->total_amount, 2) }}
                                    </span>
                                    <svg class="w-4 h-4 text-ink-faint dark:text-cream/30 group-hover:text-forest dark:group-hover:text-gold group-hover:-translate-x-1 transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>

            {{-- ════════════ PAGINATION ════════════ --}}
            <div class="mt-12 pt-8 border-t border-stone-200 dark:border-stone-800 flex justify-center">
                {{ $orders->links() }}
            </div>

        @else

            {{-- ════════════ EMPTY STATE ════════════ --}}
            <div class="text-center py-20 max-w-lg mx-auto">

                <div class="w-24 h-24 mx-auto mb-8 flex items-center justify-center border border-stone-300 dark:border-stone-700" style="border-radius: 4px;">
                    <svg class="w-10 h-10 text-ink-faint dark:text-cream/30" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                    </svg>
                </div>

                <span class="eyebrow block mb-4">— لا توجد طلبات</span>

                <h2 class="display-2 mb-4 text-balance">
                    لم تقم بأي
                    <span class="text-forest dark:text-gold">طلب بعد</span>
                </h2>

                <p class="text-base text-ink-muted dark:text-cream/60 mb-10 text-pretty">
                    ابدأ التسوق واكتشف منتجاتنا المختارة بعناية.
                </p>

                <a href="{{ route('products.index') }}" class="btn-solid group">
                    ابدأ التسوق
                    <svg class="w-4 h-4 transition-transform group-hover:-translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                    </svg>
                </a>
            </div>

        @endif

    </div>

@endsection