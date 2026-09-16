@extends('layouts.app')

@section('title', 'تفاصيل الطلب | متجري')

@section('content')

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

        <a href="{{ route('orders.index') }}"
           class="inline-flex items-center gap-1.5 text-[12px] font-medium mb-5 transition-colors duration-150"
           style="color: var(--text-secondary);"
           onmouseover="this.style.color='var(--gold)';"
           onmouseout="this.style.color='var(--text-secondary)';">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>
            العودة للطلبات
        </a>

        @php
            $statusLabels = [
                'pending' => 'قيد المراجعة',
                'processing' => 'قيد المعالجة',
                'shipped' => 'تم الشحن',
                'delivered' => 'تم التوصيل',
                'cancelled' => 'ملغي',
            ];
            $statusColors = [
                'pending' => ['#fef3c7', '#b45309'],
                'processing' => ['#dbeafe', '#1d4ed8'],
                'shipped' => ['#ede9fe', '#6d28d9'],
                'delivered' => ['#d1fae5', '#047857'],
                'cancelled' => ['#fee2e2', '#b91c1c'],
            ];
            $c = $statusColors[$order->status] ?? ['#e5e7eb', '#6b7280'];
        @endphp

        <div class="rounded-xl border mb-5" style="background-color: var(--bg-primary); border-color: var(--border-light);">
            <div class="p-5 md:p-6">

                <div class="flex flex-wrap items-center justify-between gap-4 mb-5">
                    <div class="min-w-0">
                        <h1 class="text-xl md:text-2xl font-bold mb-1" style="color: var(--text-primary);">{{ $order->order_number }}</h1>
                        <p class="text-[12px] flex items-center gap-1.5" style="color: var(--text-tertiary);">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            {{ $order->created_at->format('Y-m-d H:i') }}
                        </p>
                    </div>

                    <span class="text-[12px] font-bold px-3 py-1.5 rounded-lg"
                          style="background-color: {{ $c[0] }}; color: {{ $c[1] }};">
                        {{ $statusLabels[$order->status] ?? $order->status }}
                    </span>
                </div>

                @if($order->status !== 'cancelled')
                    <div class="pt-5 border-t" style="border-color: var(--border-light);">
                        <h3 class="text-[12px] font-semibold mb-6" style="color: var(--text-primary);">حالة الطلب</h3>

                        <div class="relative">
                            <div class="absolute top-5 right-5 left-5 h-0.5 rounded-full" style="background-color: var(--border-light);"></div>

                            <div class="absolute top-5 right-5 h-0.5 rounded-full transition-all duration-500"
                                 style="width: calc((100% - 40px) * {{ $progress }} / 100); background-color: var(--gold);"></div>

                            <div class="relative flex justify-between">
                                @php
                                    $steps = [
                                        ['key' => 'pending', 'label' => 'تم الاستلام'],
                                        ['key' => 'processing', 'label' => 'قيد المعالجة'],
                                        ['key' => 'shipped', 'label' => 'تم الشحن'],
                                        ['key' => 'delivered', 'label' => 'تم التوصيل'],
                                    ];
                                @endphp

                                @foreach($steps as $index => $step)
                                    @php
                                        $isCompleted = $currentStep >= $index;
                                        $isCurrent = $currentStep === $index;
                                    @endphp

                                    <div class="flex flex-col items-center text-center" style="flex: 1;">
                                        <div class="w-10 h-10 rounded-full flex items-center justify-center transition-all duration-300
                                                    {{ $isCurrent ? 'ring-4' : '' }}"
                                             style="background-color: {{ $isCompleted ? 'var(--gold)' : 'var(--bg-tertiary)' }};
                                                    color: {{ $isCompleted ? 'white' : 'var(--text-tertiary)' }};
                                                    {{ $isCurrent ? '--tw-ring-color: var(--gold-soft);' : '' }}">
                                            @if($index === 0)
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                                </svg>
                                            @elseif($index === 1)
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                </svg>
                                            @elseif($index === 2)
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0" />
                                                </svg>
                                            @else
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                                </svg>
                                            @endif
                                        </div>
                                        <p class="text-[11px] mt-2.5 font-medium"
                                           style="color: {{ $isCompleted ? 'var(--text-primary)' : 'var(--text-tertiary)' }};">
                                            {{ $step['label'] }}
                                        </p>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @else
                    <div class="pt-5 border-t" style="border-color: var(--border-light);">
                        <div class="rounded-xl p-6 text-center" style="background-color: #fef2f2; border: 1px solid #fecaca;">
                            <div class="w-12 h-12 rounded-xl flex items-center justify-center mx-auto mb-3" style="background-color: #dc2626;">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </div>
                            <h3 class="text-[15px] font-bold mb-1" style="color: #991b1b;">تم إلغاء هذا الطلب</h3>
                            <p class="text-[12px]" style="color: #b91c1c;">إذا كان لديك استفسار، تواصل معنا.</p>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <div class="rounded-xl border overflow-hidden mb-5" style="background-color: var(--bg-primary); border-color: var(--border-light);">
            <div class="px-5 py-4 border-b" style="border-color: var(--border-light);">
                <h2 class="text-[14px] font-semibold" style="color: var(--text-primary);">
                    المنتجات ({{ $order->items->count() }})
                </h2>
            </div>

            <div class="divide-y" style="border-color: var(--border-light);">
                @foreach($order->items as $item)
                    <div class="p-4 flex items-center gap-4">
                        <div class="w-16 h-16 rounded-lg overflow-hidden shrink-0" style="background-color: var(--bg-tertiary);">
                            @if($item->variant->product->images->first())
                                <img src="{{ asset('storage/' . $item->variant->product->images->first()->image_path) }}"
                                     class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center" style="color: var(--text-tertiary);">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                    </svg>
                                </div>
                            @endif
                        </div>

                        <div class="flex-1 min-w-0">
                            <a href="{{ route('products.show', $item->variant->product->slug) }}"
                               class="block text-[13px] font-medium mb-0.5 transition-colors duration-150 truncate"
                               style="color: var(--text-primary);"
                               onmouseover="this.style.color='var(--gold)';"
                               onmouseout="this.style.color='var(--text-primary)';">
                                {{ $item->variant->product->name }}
                            </a>
                            @if($item->variant->color || $item->variant->size)
                                <p class="text-[11px]" style="color: var(--text-tertiary);">
                                    {{ $item->variant->color ?? '' }}{{ ($item->variant->color && $item->variant->size) ? ' · ' : '' }}{{ $item->variant->size ?? '' }}
                                </p>
                            @endif
                        </div>

                        <div class="text-left shrink-0">
                            <p class="text-[11px] mb-0.5" style="color: var(--text-tertiary);">
                                {{ $item->quantity }} × ${{ number_format($item->unit_price, 2) }}
                            </p>
                            <p class="text-[13px] font-bold" style="color: var(--text-primary);">
                                ${{ number_format($item->quantity * $item->unit_price, 2) }}
                            </p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="grid md:grid-cols-2 gap-5">

            <div class="rounded-xl border p-5" style="background-color: var(--bg-primary); border-color: var(--border-light);">
                <div class="flex items-center gap-2 mb-4">
                    <svg class="w-4 h-4" style="color: var(--gold);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    <h3 class="text-[13px] font-semibold" style="color: var(--text-primary);">عنوان الشحن</h3>
                </div>

                <div class="space-y-1.5 text-[12px]">
                    <p class="font-semibold" style="color: var(--text-primary);">{{ $order->address->city->name ?? '' }}</p>
                    <p style="color: var(--text-secondary);">{{ $order->address->street_address ?? '' }}</p>
                    <p style="color: var(--text-secondary);">{{ $order->address->phone ?? '' }}</p>
                    @if($order->address->notes)
                        <p class="pt-2 mt-2 border-t text-[11px]" style="border-color: var(--border-light); color: var(--text-tertiary);">
                            {{ $order->address->notes }}
                        </p>
                    @endif
                </div>
            </div>

            <div class="rounded-xl border p-5" style="background-color: var(--bg-primary); border-color: var(--border-light);">
                <div class="flex items-center gap-2 mb-4">
                    <svg class="w-4 h-4" style="color: var(--gold);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                    </svg>
                    <h3 class="text-[13px] font-semibold" style="color: var(--text-primary);">ملخص الفاتورة</h3>
                </div>

                <div class="space-y-2.5 text-[12px]">
                    <div class="flex justify-between" style="color: var(--text-secondary);">
                        <span>المجموع الفرعي</span>
                        <span style="color: var(--text-primary);">
                            ${{ number_format($order->total_amount - $order->shipping_cost, 2) }}
                        </span>
                    </div>

                    <div class="flex justify-between" style="color: var(--text-secondary);">
                        <span>الشحن</span>
                        @if($order->shipping_cost > 0)
                            <span style="color: var(--text-primary);">${{ number_format($order->shipping_cost, 2) }}</span>
                        @else
                            <span style="color: #166534;">مجاني</span>
                        @endif
                    </div>

                    <div class="my-3 border-t" style="border-color: var(--border-light);"></div>

                    <div class="flex justify-between items-baseline">
                        <span class="text-[13px] font-bold" style="color: var(--text-primary);">الإجمالي</span>
                        <span class="text-lg font-bold" style="color: var(--gold);">${{ number_format($order->total_amount, 2) }}</span>
                    </div>
                </div>

                <div class="mt-4 pt-4 border-t flex justify-between items-center text-[11px]" style="border-color: var(--border-light);">
                    <span style="color: var(--text-tertiary);">حالة الدفع</span>
                    @if($order->payment_status === 'paid')
                        <span class="font-semibold px-2 py-0.5 rounded" style="background-color: #d1fae5; color: #047857;">مدفوع</span>
                    @elseif($order->payment_status === 'refunded')
                        <span class="font-semibold px-2 py-0.5 rounded" style="background-color: #fee2e2; color: #b91c1c;">مسترجع</span>
                    @else
                        <span class="font-semibold px-2 py-0.5 rounded" style="background-color: #fef3c7; color: #b45309;">غير مدفوع</span>
                    @endif
                </div>
            </div>

        </div>

    </div>

@endsection