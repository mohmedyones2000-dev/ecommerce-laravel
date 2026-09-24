@extends('layouts.app')

@section('title', 'تفاصيل الطلب | متجري')

@section('content')

<div class="container-narrow pt-12 lg:pt-16 pb-24">

    {{-- ════════════ BREADCRUMB + BACK ════════════ --}}
    <nav class="flex items-center gap-3 text-[10px] font-medium uppercase tracking-[0.2em] text-ink-faint dark:text-cream/40 mb-10 flex-wrap">
        <a href="{{ route('home') }}" class="hover:text-forest dark:hover:text-gold transition-colors">الرئيسية</a>
        <span class="opacity-40">/</span>
        <a href="{{ route('orders.index') }}" class="hover:text-forest dark:hover:text-gold transition-colors">طلباتي</a>
        <span class="opacity-40">/</span>
        <span class="text-ink dark:text-cream">{{ $order->order_number }}</span>
    </nav>

    @php
        $statusMap = [
            'pending'    => ['label' => 'قيد المراجعة', 'color' => 'text-amber-700 dark:text-amber-300', 'bg' => 'bg-amber-50 dark:bg-amber-950/30', 'border' => 'border-amber-200 dark:border-amber-900/50'],
            'processing' => ['label' => 'قيد المعالجة', 'color' => 'text-blue-700 dark:text-blue-300', 'bg' => 'bg-blue-50 dark:bg-blue-950/30', 'border' => 'border-blue-200 dark:border-blue-900/50'],
            'shipped'    => ['label' => 'تم الشحن',    'color' => 'text-violet-700 dark:text-violet-300', 'bg' => 'bg-violet-50 dark:bg-violet-950/30', 'border' => 'border-violet-200 dark:border-violet-900/50'],
            'delivered'  => ['label' => 'تم التوصيل', 'color' => 'text-green-700 dark:text-green-300', 'bg' => 'bg-green-50 dark:bg-green-950/30', 'border' => 'border-green-200 dark:border-green-900/50'],
            'cancelled'  => ['label' => 'ملغي',        'color' => 'text-red-700 dark:text-red-300', 'bg' => 'bg-red-50 dark:bg-red-950/30', 'border' => 'border-red-200 dark:border-red-900/50'],
        ];
        $status = $statusMap[$order->status] ?? ['label' => $order->status, 'color' => 'text-ink-muted', 'bg' => 'bg-stone-100 dark:bg-stone-800', 'border' => 'border-stone-200 dark:border-stone-700'];

        $paymentStatusMap = [
            'paid'     => ['label' => 'مدفوع',    'color' => 'text-green-700 dark:text-green-300', 'bg' => 'bg-green-50 dark:bg-green-950/30', 'border' => 'border-green-200 dark:border-green-900/50'],
            'unpaid'   => ['label' => 'غير مدفوع', 'color' => 'text-amber-700 dark:text-amber-300', 'bg' => 'bg-amber-50 dark:bg-amber-950/30', 'border' => 'border-amber-200 dark:border-amber-900/50'],
            'refunded' => ['label' => 'مسترجع',    'color' => 'text-red-700 dark:text-red-300', 'bg' => 'bg-red-50 dark:bg-red-950/30', 'border' => 'border-red-200 dark:border-red-900/50'],
        ];
        $payment = $paymentStatusMap[$order->payment_status] ?? ['label' => $order->payment_status, 'color' => 'text-ink-muted', 'bg' => 'bg-stone-100 dark:bg-stone-800', 'border' => 'border-stone-200 dark:border-stone-700'];
    @endphp

    {{-- ════════════ HEADER ════════════ --}}
    <header class="mb-12 lg:mb-16 pb-8 border-b border-stone-200 dark:border-stone-800">

        <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-6 mb-8">
            <div>
                <span class="eyebrow block mb-4">— الطلب</span>
                <h1 class="display-2 mb-3">
                    <span dir="ltr">{{ $order->order_number }}</span>
                </h1>
                <p class="text-sm text-ink-muted dark:text-cream/60 flex items-center gap-2">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    <span dir="ltr">{{ $order->created_at->format('Y-m-d · H:i') }}</span>
                </p>
            </div>

            {{-- Badges --}}
            <div class="flex items-center gap-2 shrink-0">
                <span class="inline-flex items-center gap-2 px-3 py-1.5 text-[10px] font-bold tracking-widest uppercase border {{ $status['bg'] }} {{ $status['border'] }} {{ $status['color'] }}" style="border-radius: 2px;">
                    <span class="w-1.5 h-1.5 rounded-full {{ str_replace('text-', 'bg-', $status['color']) }}"></span>
                    {{ $status['label'] }}
                </span>
                <span class="inline-flex items-center px-3 py-1.5 text-[10px] font-bold tracking-widest uppercase border {{ $payment['bg'] }} {{ $payment['border'] }} {{ $payment['color'] }}" style="border-radius: 2px;">
                    {{ $payment['label'] }}
                </span>
            </div>
        </div>

        {{-- Tracking Steps --}}
        @if($order->status !== 'cancelled')
            <div class="border border-stone-200 dark:border-stone-800 p-8" style="border-radius: 4px;">
                <p class="text-[10px] font-semibold tracking-widest uppercase text-ink-muted dark:text-cream/60 mb-8">
                    مسار الطلب
                </p>

                <div class="relative">
                    {{-- Track line --}}
                    <div class="absolute top-5 right-5 left-5 h-0.5 bg-stone-200 dark:bg-stone-800" aria-hidden="true"></div>

                    {{-- Progress line --}}
                    <div class="absolute top-5 right-5 h-0.5 bg-forest dark:bg-gold transition-all duration-700" style="width: calc((100% - 40px) * {{ $progress }} / 100);" aria-hidden="true"></div>

                    <div class="relative flex justify-between">
                        @php
                            $steps = [
                                ['key' => 'pending',    'label' => 'تم الاستلام',   'icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2'],
                                ['key' => 'processing', 'label' => 'قيد المعالجة', 'icon' => 'M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065zM15 12a3 3 0 11-6 0 3 3 0 016 0z'],
                                ['key' => 'shipped',    'label' => 'تم الشحن',      'icon' => 'M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0'],
                                ['key' => 'delivered',  'label' => 'تم التوصيل',   'icon' => 'M5 13l4 4L19 7'],
                            ];
                        @endphp

                        @foreach($steps as $index => $step)
                            @php
                                $isCompleted = $currentStep >= $index;
                                $isCurrent = $currentStep === $index;
                            @endphp

                            <div class="flex flex-col items-center text-center" style="flex: 1;">
                                <div class="w-10 h-10 flex items-center justify-center transition-all duration-300
                                            {{ $isCompleted ? 'bg-forest dark:bg-gold text-cream dark:text-ink' : 'bg-stone-100 dark:bg-stone-800 text-ink-faint dark:text-cream/30' }}
                                            {{ $isCurrent ? 'ring-4 ring-forest/15 dark:ring-gold/20' : '' }}"
                                     style="border-radius: 4px;">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="{{ $step['icon'] }}" />
                                    </svg>
                                </div>
                                <p class="text-[10px] font-semibold tracking-widest uppercase mt-3 {{ $isCompleted ? 'text-ink dark:text-cream' : 'text-ink-faint dark:text-cream/30' }}">
                                    {{ $step['label'] }}
                                </p>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @else
            {{-- Cancelled Notice --}}
            <div class="border-2 border-red-200 dark:border-red-900/50 bg-red-50 dark:bg-red-950/20 p-8 text-center" style="border-radius: 4px;">
                <div class="w-14 h-14 mx-auto mb-5 flex items-center justify-center bg-red-600 text-white" style="border-radius: 4px;">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </div>
                <p class="font-display text-lg font-bold text-red-800 dark:text-red-300 mb-2">
                    تم إلغاء هذا الطلب
                </p>
                <p class="text-sm text-red-700 dark:text-red-400">
                    إذا كان لديك استفسار، تواصل معنا.
                </p>
            </div>
        @endif
    </header>

    {{-- ════════════ ITEMS ════════════ --}}
    <section class="mb-12">
        <div class="flex items-center justify-between mb-6">
            <div>
                <span class="eyebrow block mb-2">— المحتوى</span>
                <h2 class="font-display text-2xl font-bold">
                    المنتجات <span class="text-forest dark:text-gold">({{ $order->items->count() }})</span>
                </h2>
            </div>
        </div>

        <div class="border border-stone-200 dark:border-stone-800 divide-y divide-stone-200 dark:divide-stone-800" style="border-radius: 4px;">
            @foreach($order->items as $item)
                <div class="p-5 flex items-center gap-5">

                    {{-- Image --}}
                    <a href="{{ route('products.show', $item->variant->product->slug) }}"
                       class="w-20 h-20 shrink-0 overflow-hidden bg-cream-warm dark:bg-zinc-900 hover:scale-105 transition-transform"
                       style="border-radius: 4px;">
                        @if($item->variant->product->images->first())
                            <img src="{{ asset('storage/' . $item->variant->product->images->first()->image_path) }}"
                                 alt="{{ $item->variant->product->name }}"
                                 class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-ink-faint dark:text-cream/30">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                </svg>
                            </div>
                        @endif
                    </a>

                    {{-- Info --}}
                    <div class="flex-1 min-w-0">
                        <a href="{{ route('products.show', $item->variant->product->slug) }}"
                           class="font-display text-sm font-bold mb-2 hover:text-forest dark:hover:text-gold transition-colors line-clamp-2 block">
                            {{ $item->variant->product->name }}
                        </a>
                        @if($item->variant->color || $item->variant->size)
                            <p class="text-xs text-ink-muted dark:text-cream/50">
                                {{ $item->variant->color ?? '' }}{{ ($item->variant->color && $item->variant->size) ? ' · ' : '' }}{{ $item->variant->size ?? '' }}
                            </p>
                        @endif
                    </div>

                    {{-- Price --}}
                    <div class="text-left shrink-0">
                        <p class="font-display text-base font-bold text-forest dark:text-gold">
                            ${{ number_format($item->quantity * $item->unit_price, 2) }}
                        </p>
                        <p class="text-xs text-ink-muted dark:text-cream/50 mt-0.5">
                            {{ $item->quantity }} × ${{ number_format($item->unit_price, 2) }}
                        </p>
                    </div>
                </div>
            @endforeach
        </div>
    </section>

    {{-- ════════════ ADDRESS + INVOICE ════════════ --}}
    <div class="grid lg:grid-cols-2 gap-6">

        {{-- Address --}}
        <div class="border border-stone-200 dark:border-stone-800 p-6" style="border-radius: 4px;">
            <div class="flex items-center gap-3 mb-6">
                <div class="w-10 h-10 flex items-center justify-center bg-forest/5 dark:bg-gold/10 text-forest dark:text-gold" style="border-radius: 4px;">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                </div>
                <h3 class="font-display text-sm font-bold tracking-widest uppercase">
                    عنوان الشحن
                </h3>
            </div>

            <div class="space-y-3 text-sm">
                <div>
                    <p class="text-[10px] font-semibold tracking-widest uppercase text-ink-muted dark:text-cream/50 mb-1">المدينة</p>
                    <p class="font-medium">{{ $order->address->city->name ?? '—' }}</p>
                </div>
                <div>
                    <p class="text-[10px] font-semibold tracking-widest uppercase text-ink-muted dark:text-cream/50 mb-1">العنوان</p>
                    <p class="text-ink-soft dark:text-cream/80 leading-relaxed">{{ $order->address->street_address ?? '—' }}</p>
                </div>
                <div>
                    <p class="text-[10px] font-semibold tracking-widest uppercase text-ink-muted dark:text-cream/50 mb-1">الهاتف</p>
                    <p dir="ltr" class="text-left font-medium">{{ $order->address->phone ?? '—' }}</p>
                </div>
                @if($order->address->notes)
                    <div class="pt-3 mt-3 border-t border-stone-200 dark:border-stone-800">
                        <p class="text-[10px] font-semibold tracking-widest uppercase text-ink-muted dark:text-cream/50 mb-1">ملاحظات</p>
                        <p class="text-xs leading-relaxed text-ink-muted dark:text-cream/60">{{ $order->address->notes }}</p>
                    </div>
                @endif
            </div>
        </div>

        {{-- Invoice Summary --}}
        <div class="border border-stone-200 dark:border-stone-800 p-6" style="border-radius: 4px;">
            <div class="flex items-center gap-3 mb-6">
                <div class="w-10 h-10 flex items-center justify-center bg-forest/5 dark:bg-gold/10 text-forest dark:text-gold" style="border-radius: 4px;">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                    </svg>
                </div>
                <h3 class="font-display text-sm font-bold tracking-widest uppercase">
                    ملخص الفاتورة
                </h3>
            </div>

            <div class="space-y-3 text-sm pb-5 mb-5 border-b border-stone-200 dark:border-stone-800">
                <div class="flex justify-between">
                    <span class="text-ink-muted dark:text-cream/60">المجموع الفرعي</span>
                    <span class="font-medium">${{ number_format($order->total_amount - $order->shipping_cost, 2) }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-ink-muted dark:text-cream/60">الشحن</span>
                    @if($order->shipping_cost > 0)
                        <span class="font-medium">${{ number_format($order->shipping_cost, 2) }}</span>
                    @else
                        <span class="font-medium text-green-600 dark:text-green-400">مجاني</span>
                    @endif
                </div>
            </div>

            <div class="flex items-baseline justify-between mb-5">
                <span class="font-display text-base font-bold">الإجمالي</span>
                <span class="font-display text-3xl font-bold text-forest dark:text-gold">
                    ${{ number_format($order->total_amount, 2) }}
                </span>
            </div>

            <div class="pt-5 border-t border-stone-200 dark:border-stone-800 flex items-center justify-between text-xs">
                <span class="text-[10px] font-semibold tracking-widest uppercase text-ink-muted dark:text-cream/60">حالة الدفع</span>
                <span class="inline-flex items-center gap-2 px-3 py-1.5 text-[10px] font-bold tracking-widest uppercase border {{ $payment['bg'] }} {{ $payment['border'] }} {{ $payment['color'] }}" style="border-radius: 2px;">
                    {{ $payment['label'] }}
                </span>
            </div>

            {{-- Invoice download --}}
            @if(Route::has('orders.invoice'))
                <a href="{{ route('orders.invoice', $order->id) }}"
                   target="_blank"
                   class="mt-6 group flex items-center justify-center gap-3 w-full h-12 font-display font-bold text-xs tracking-widest uppercase border border-stone-300 dark:border-stone-700 hover:border-forest dark:hover:border-gold hover:text-forest dark:hover:text-gold hover:bg-forest/5 dark:hover:bg-gold/10 transition-colors"
                   style="border-radius: 4px;">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    تحميل الفاتورة
                </a>
            @endif
        </div>
    </div>

    {{-- ════════════ BACK TO ORDERS ════════════ --}}
    <div class="mt-12 pt-8 border-t border-stone-200 dark:border-stone-800">
        <a href="{{ route('orders.index') }}" class="link-arrow group inline-flex">
            <svg class="w-4 h-4 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
            </svg>
            العودة لكل الطلبات
        </a>
    </div>

</div>

@endsection