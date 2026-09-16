@extends('layouts.app')

@section('title', 'طلباتي | متجري')

@section('content')

    <div class="max-w-[1400px] mx-auto px-4 sm:px-6 lg:px-8 py-8">

        <div class="mb-8">
            <h1 class="text-2xl md:text-3xl font-bold mb-1.5" style="color: var(--text-primary);">طلباتي</h1>
            <p class="text-[13px]" style="color: var(--text-secondary);">تابع حالة جميع طلباتك</p>
        </div>

        @if(session('success'))
            <div class="px-4 py-3 rounded-lg mb-5 text-[13px]"
                style="background-color: #f0fdf4; color: #166534; border: 1px solid #bbf7d0;">
                {{ session('success') }}
            </div>
        @endif

        @if($orders->count())
            <div class="space-y-4">
                @foreach($orders as $order)
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

                        $statusSteps = ['pending', 'processing', 'shipped', 'delivered'];
                        $currentStep = array_search($order->status, $statusSteps);
                        $progress = $order->status === 'cancelled' ? 0 : (($currentStep + 1) / count($statusSteps)) * 100;
                    @endphp

                    <div class="rounded-xl border overflow-hidden transition-all duration-200"
                        style="background-color: var(--bg-primary); border-color: var(--border-light);"
                        onmouseover="this.style.borderColor='var(--gold)';"
                        onmouseout="this.style.borderColor='var(--border-light)';">

                        <div class="p-5">

                            <div class="flex items-center justify-between gap-4 mb-4">
                                <div class="min-w-0">
                                    <p class="text-[11px] mb-0.5" style="color: var(--text-tertiary);">رقم الطلب</p>
                                    <p class="text-[14px] font-bold truncate" style="color: var(--text-primary);">
                                        {{ $order->order_number }}</p>
                                </div>

                                <span class="text-[11px] font-semibold px-2.5 py-1 rounded shrink-0"
                                    style="background-color: {{ $c[0] }}; color: {{ $c[1] }};">
                                    {{ $statusLabels[$order->status] ?? $order->status }}
                                </span>
                            </div>

                            @if($order->status !== 'cancelled')
                                <div class="mb-4">
                                    <div class="h-1.5 rounded-full overflow-hidden" style="background-color: var(--bg-tertiary);">
                                        <div class="h-full rounded-full transition-all duration-500"
                                            style="width: {{ $progress }}%; background-color: var(--gold);"></div>
                                    </div>
                                    <div class="flex justify-between mt-2.5 text-[11px]" style="color: var(--text-tertiary);">
                                        <span>الاستلام</span>
                                        <span>المعالجة</span>
                                        <span>الشحن</span>
                                        <span>التوصيل</span>
                                    </div>
                                </div>
                            @endif

                            <div class="flex items-center justify-between pt-4 border-t gap-4"
                                style="border-color: var(--border-light);">
                                <div class="text-[12px] flex items-center gap-2" style="color: var(--text-tertiary);">
                                    <span>{{ $order->items_count }} {{ $order->items_count == 1 ? 'منتج' : 'منتجات' }}</span>
                                    <span>·</span>
                                    <span>{{ $order->created_at->format('Y-m-d') }}</span>
                                </div>

                                <div class="flex items-center gap-4 shrink-0">
                                    <span class="text-[14px] font-bold"
                                        style="color: var(--gold);">${{ number_format($order->total_amount, 2) }}</span>
                                    <a href="{{ route('orders.show', $order->id) }}"
                                        class="text-[12px] font-semibold flex items-center gap-1 transition-colors duration-150"
                                        style="color: var(--gold);" onmouseover="this.style.opacity='0.7';"
                                        onmouseout="this.style.opacity='1';">
                                        التفاصيل
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 19l-7-7 7-7" />
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-6 flex justify-center">
                {{ $orders->links() }}
            </div>
        @else
            <div class="rounded-xl border p-14 text-center max-w-lg mx-auto"
                style="background-color: var(--bg-primary); border-color: var(--border-light);">
                <div class="w-16 h-16 rounded-xl flex items-center justify-center mx-auto mb-5"
                    style="background-color: var(--bg-tertiary);">
                    <svg class="w-8 h-8" style="color: var(--text-tertiary);" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                    </svg>
                </div>
                <h2 class="text-lg font-bold mb-2" style="color: var(--text-primary);">لا توجد طلبات</h2>
                <p class="text-[13px] mb-6" style="color: var(--text-secondary);">لم تقم بأي طلبات حتى الآن.</p>
                <a href="{{ route('products.index') }}"
                    class="inline-flex items-center gap-2 h-11 px-6 rounded-lg font-semibold text-[13px] text-white transition-all duration-200 hover:opacity-90"
                    style="background-color: var(--gold);">
                    ابدأ التسوق
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </a>
            </div>
        @endif

    </div>

@endsection