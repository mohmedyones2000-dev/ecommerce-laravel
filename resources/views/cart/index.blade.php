@extends('layouts.app')

@section('title', 'سلة المشتريات | متجري')

@section('content')

    <div class="max-w-[1400px] mx-auto px-4 sm:px-6 lg:px-8 py-8">

        <div class="mb-8">
            <h1 class="text-2xl md:text-3xl font-bold mb-1.5" style="color: var(--text-primary);">سلة المشتريات</h1>
            <p class="text-[13px]" style="color: var(--text-secondary);">مراجعة المنتجات قبل إتمام الطلب</p>
        </div>

        @if(session('success'))
            <div class="px-4 py-3 rounded-lg mb-5 text-[13px]" style="background-color: #f0fdf4; color: #166534; border: 1px solid #bbf7d0;">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="px-4 py-3 rounded-lg mb-5 text-[13px]" style="background-color: #fef2f2; color: #991b1b; border: 1px solid #fecaca;">
                {{ session('error') }}
            </div>
        @endif

        @if(count($items) > 0)

            <div class="grid md:grid-cols-3 gap-6">

                <div class="md:col-span-2 space-y-4">

                    <div class="rounded-xl border overflow-hidden" style="background-color: var(--bg-primary); border-color: var(--border-light);">
                        <div class="px-5 py-4 border-b" style="border-color: var(--border-light);">
                            <h2 class="text-[14px] font-semibold" style="color: var(--text-primary);">
                                المنتجات ({{ count($items) }})
                            </h2>
                        </div>

                        <div class="divide-y" style="border-color: var(--border-light);">
                            @foreach($items as $item)
                                <div class="p-4 flex gap-4">
                                    <a href="{{ route('products.show', $item['product']) }}"
                                       class="w-20 h-20 rounded-lg overflow-hidden shrink-0"
                                       style="background-color: var(--bg-tertiary);">
                                        @if($item['product']->images->first())
                                            <img src="{{ asset('storage/' . $item['product']->images->first()->image_path) }}"
                                                 alt="{{ $item['product']->name }}"
                                                 class="w-full h-full object-cover">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center" style="color: var(--text-tertiary);">
                                                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                                </svg>
                                            </div>
                                        @endif
                                    </a>

                                    <div class="flex-1 min-w-0">
                                        <a href="{{ route('products.show', $item['product']) }}"
                                           class="block text-[14px] font-medium mb-1 transition-colors duration-150"
                                           style="color: var(--text-primary);"
                                           onmouseover="this.style.color='var(--gold)';"
                                           onmouseout="this.style.color='var(--text-primary)';">
                                            {{ $item['product']->name }}
                                        </a>

                                        @if($item['variant']->color || $item['variant']->size)
                                            <p class="text-[12px] mb-2" style="color: var(--text-tertiary);">
                                                @if($item['variant']->color)
                                                    <span>{{ $item['variant']->color }}</span>
                                                @endif
                                                @if($item['variant']->color && $item['variant']->size)
                                                    <span> · </span>
                                                @endif
                                                @if($item['variant']->size)
                                                    <span>{{ $item['variant']->size }}</span>
                                                @endif
                                            </p>
                                        @endif

                                        <p class="text-[15px] font-bold" style="color: var(--gold);">
                                            ${{ number_format($item['product']->discount_price ?? $item['product']->price, 2) }}
                                        </p>
                                    </div>

                                    <div class="flex flex-col justify-between items-end gap-3">
                                        <form action="{{ route('cart.remove') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="variant_id" value="{{ $item['variant_id'] }}">
                                            <button type="submit" title="حذف"
                                                    class="w-8 h-8 rounded-lg flex items-center justify-center transition-colors duration-150"
                                                    style="color: var(--text-tertiary);"
                                                    onmouseover="this.style.backgroundColor='#fef2f2'; this.style.color='#dc2626';"
                                                    onmouseout="this.style.backgroundColor='transparent'; this.style.color='var(--text-tertiary)';">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                                          d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </form>

                                        <form action="{{ route('cart.update') }}" method="POST"
                                              class="flex items-center border rounded-lg overflow-hidden"
                                              style="border-color: var(--border-light); background-color: var(--bg-primary);">
                                            @csrf
                                            <input type="hidden" name="variant_id" value="{{ $item['variant_id'] }}">
                                            <button type="submit" name="quantity" value="{{ $item['quantity'] - 1 }}"
                                                    class="w-8 h-8 flex items-center justify-center text-[16px] font-bold transition-colors duration-150"
                                                    style="color: var(--text-secondary);"
                                                    onmouseover="this.style.color='var(--gold)';"
                                                    onmouseout="this.style.color='var(--text-secondary)';">
                                                −
                                            </button>
                                            <span class="w-9 text-center text-[13px] font-semibold" style="color: var(--text-primary);">
                                                {{ $item['quantity'] }}
                                            </span>
                                            <button type="submit" name="quantity" value="{{ $item['quantity'] + 1 }}"
                                                    class="w-8 h-8 flex items-center justify-center text-[16px] font-bold transition-colors duration-150"
                                                    style="color: var(--text-secondary);"
                                                    onmouseover="this.style.color='var(--gold)';"
                                                    onmouseout="this.style.color='var(--text-secondary)';">
                                                +
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="rounded-xl border p-5" style="background-color: var(--bg-primary); border-color: var(--border-light);">
                        <h3 class="text-[14px] font-semibold mb-4" style="color: var(--text-primary);">كود الخصم</h3>

                        @if($coupon)
                            <div class="flex items-center justify-between rounded-lg p-3.5 border-2" style="background-color: #f0fdf4; border-color: #bbf7d0;">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-lg flex items-center justify-center" style="background-color: #166534;">
                                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-[13px] font-bold" style="color: #166534;">{{ $coupon->code }}</p>
                                        <p class="text-[11px]" style="color: #15803d;">
                                            @if($coupon->type === 'percentage')
                                                خصم {{ $coupon->value }}%
                                            @else
                                                خصم ${{ number_format($coupon->value, 2) }}
                                            @endif
                                            @if($coupon->free_shipping)
                                                · شحن مجاني
                                            @endif
                                        </p>
                                    </div>
                                </div>
                                <form action="{{ route('coupon.remove') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="text-[12px] font-semibold transition-colors duration-150"
                                            style="color: #dc2626;"
                                            onmouseover="this.style.opacity='0.7';"
                                            onmouseout="this.style.opacity='1';">
                                        إزالة
                                    </button>
                                </form>
                            </div>
                        @else
                            <form action="{{ route('coupon.apply') }}" method="POST" class="flex gap-2">
                                @csrf
                                <input type="text" name="code" required placeholder="أدخل كود الخصم"
                                       class="flex-1 h-10 rounded-lg px-3 text-[13px] focus:outline-none focus:ring-2 transition-all duration-150 uppercase"
                                       style="background-color: var(--bg-primary); border: 1px solid var(--border-light); color: var(--text-primary); --tw-ring-color: var(--gold);">
                                <button type="submit"
                                        class="h-10 px-5 rounded-lg font-semibold text-[13px] text-white transition-all duration-200 hover:opacity-90"
                                        style="background-color: var(--gold);">
                                    تطبيق
                                </button>
                            </form>
                        @endif
                    </div>
                </div>

                <div class="md:col-span-1">
                    <div class="rounded-xl border p-5 sticky top-20" style="background-color: var(--bg-primary); border-color: var(--border-light);">
                        <h2 class="text-[15px] font-bold mb-5" style="color: var(--text-primary);">ملخص الطلب</h2>

                        <div class="space-y-3 text-[13px]">
                            <div class="flex justify-between" style="color: var(--text-secondary);">
                                <span>المجموع الفرعي</span>
                                <span style="color: var(--text-primary);">${{ number_format($total, 2) }}</span>
                            </div>

                            @if($discount > 0)
                                <div class="flex justify-between" style="color: #166534;">
                                    <span>الخصم ({{ $coupon->code }})</span>
                                    <span>-${{ number_format($discount, 2) }}</span>
                                </div>
                            @endif

                            <div class="flex justify-between" style="color: var(--text-secondary);">
                                <span>الشحن</span>
                                @if($selectedCity)
                                    @if($shipping > 0)
                                        <span style="color: var(--text-primary);">${{ number_format($shipping, 2) }}</span>
                                    @else
                                        <span style="color: #166534;">مجاني</span>
                                    @endif
                                @else
                                    <span class="text-[11px]" style="color: var(--text-tertiary);">يُحدد في صفحة الدفع</span>
                                @endif
                            </div>
                        </div>

                        <div class="my-4 border-t" style="border-color: var(--border-light);"></div>

                        <div class="flex justify-between items-baseline mb-5">
                            <span class="text-[14px] font-bold" style="color: var(--text-primary);">الإجمالي</span>
                            <span class="text-xl font-bold" style="color: var(--gold);">${{ number_format($finalTotal, 2) }}</span>
                        </div>

                        <a href="{{ route('checkout.index') }}"
                           class="block w-full h-11 rounded-lg font-semibold text-[13px] text-white text-center leading-[44px] transition-all duration-200 hover:opacity-90"
                           style="background-color: var(--gold);">
                            إتمام الطلب
                        </a>

                        <a href="{{ route('products.index') }}"
                           class="block w-full h-11 rounded-lg font-semibold text-[13px] text-center leading-[44px] mt-2 transition-all duration-150"
                           style="background-color: var(--bg-tertiary); color: var(--text-primary);"
                           onmouseover="this.style.backgroundColor='var(--gold-soft)'; this.style.color='var(--gold)';"
                           onmouseout="this.style.backgroundColor='var(--bg-tertiary)'; this.style.color='var(--text-primary)';">
                            متابعة التسوق
                        </a>

                        <div class="mt-5 pt-5 border-t space-y-2.5" style="border-color: var(--border-light);">
                            <div class="flex items-center gap-2.5 text-[12px]" style="color: var(--text-secondary);">
                                <svg class="w-4 h-4" style="color: var(--gold);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                </svg>
                                <span>دفع آمن 100%</span>
                            </div>
                            <div class="flex items-center gap-2.5 text-[12px]" style="color: var(--text-secondary);">
                                <svg class="w-4 h-4" style="color: var(--gold);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                </svg>
                                <span>إرجاع مجاني خلال 14 يوم</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        @else
            <div class="rounded-xl border p-14 text-center max-w-lg mx-auto" style="background-color: var(--bg-primary); border-color: var(--border-light);">
                <div class="w-16 h-16 rounded-xl flex items-center justify-center mx-auto mb-5" style="background-color: var(--bg-tertiary);">
                    <svg class="w-8 h-8" style="color: var(--text-tertiary);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>
                <h2 class="text-lg font-bold mb-2" style="color: var(--text-primary);">سلتك فارغة</h2>
                <p class="text-[13px] mb-6" style="color: var(--text-secondary);">لم تقم بإضافة أي منتجات إلى السلة بعد.</p>
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