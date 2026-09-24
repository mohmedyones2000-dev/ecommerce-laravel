@extends('layouts.app')

@section('title', 'إتمام الطلب | متجري')

@section('content')

<style>
    .checkout-page {
        --gold: #14b8a6;
        --gold-soft: rgba(20, 184, 166, 0.1);
        --border-light: #e6e6e4;
        --text-primary: #0f0f0f;
        --text-secondary: #6b6b5e;
        --text-tertiary: #9a9a8c;
        --bg-primary: #fafaf9;
        --bg-secondary: #f5f5f4;
        --bg-tertiary: #f5f5f4;
    }
    .dark .checkout-page {
        --gold: #2dd4bf;
        --gold-soft: rgba(45, 212, 191, 0.15);
        --border-light: #262626;
        --text-primary: #f5f5f0;
        --text-secondary: #969690;
        --text-tertiary: #6e6e64;
        --bg-primary: #0a0a0a;
        --bg-secondary: #141414;
        --bg-tertiary: #141414;
    }
</style>

<div class="checkout-page container-x pt-12 lg:pt-16 pb-24">

    {{-- ════════════ HEADER ════════════ --}}
    <header class="mb-12 lg:mb-16 pb-8 border-b border-stone-200 dark:border-stone-800">
        <nav class="flex items-center gap-3 text-[10px] font-medium uppercase tracking-[0.2em] text-ink-faint dark:text-cream/40 mb-8" aria-label="breadcrumb">
            <a href="{{ route('home') }}" class="hover:text-forest dark:hover:text-gold transition-colors">الرئيسية</a>
            <span class="opacity-40">/</span>
            <a href="{{ route('cart.index') }}" class="hover:text-forest dark:hover:text-gold transition-colors">السلة</a>
            <span class="opacity-40">/</span>
            <span class="text-ink dark:text-cream">إتمام الطلب</span>
        </nav>

        <span class="eyebrow block mb-4">— الخطوة الأخيرة</span>
        <h1 class="display-2">
            إتمام
            <span class="text-forest dark:text-gold">الطلب</span>
        </h1>
        <p class="mt-4 text-base text-ink-muted dark:text-cream/60 text-pretty max-w-md">
            أكمل بياناتك وسنوصل طلبك إلى باب منزلك.
        </p>
    </header>

    @if(session('error'))
        <div class="px-5 py-4 mb-8 text-sm border border-red-200 dark:border-red-900/50 bg-red-50 dark:bg-red-950/20 text-red-800 dark:text-red-300 flex items-center gap-3" style="border-radius: 4px;">
            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>
            {{ session('error') }}
        </div>
    @endif

    <form action="{{ route('checkout.store') }}" method="POST" id="checkoutForm">
        @csrf

        <div class="grid lg:grid-cols-12 gap-10 lg:gap-16">

            {{-- ════════════ FORMS ════════════ --}}
            <div class="lg:col-span-7 space-y-8">

                {{-- ─── SHIPPING ─── --}}
                <section class="border border-stone-200 dark:border-stone-800" style="border-radius: 4px;">
                    <div class="flex items-center justify-between px-6 py-5 border-b border-stone-200 dark:border-stone-800">
                        <div class="flex items-center gap-3">
                            <span class="w-7 h-7 flex items-center justify-center bg-forest dark:bg-gold text-cream dark:text-ink font-display font-bold text-xs" style="border-radius: 4px;">01</span>
                            <span class="text-[10px] font-semibold tracking-widest uppercase text-ink-muted dark:text-cream/60">
                                عنوان الشحن
                            </span>
                        </div>
                    </div>

                    <div class="p-6">

                        @if($addresses->count())
                            <div class="space-y-3 mb-6">

                                {{-- Existing Address Option --}}
                                <label class="flex items-start gap-3 p-4 border-2 cursor-pointer transition-all duration-200 has-[:checked]:border-gold has-[:checked]:bg-gold/5 border-stone-200 dark:border-stone-800"
                                       style="border-radius: 4px;">
                                    <input type="radio" name="address_option" value="existing" checked
                                           class="mt-1 w-4 h-4 text-gold focus:ring-gold focus:ring-offset-0">
                                    <span class="text-sm font-semibold">استخدام عنوان محفوظ</span>
                                </label>

                                <div class="space-y-2 pr-6">
                                    @foreach($addresses as $address)
                                        <label class="flex items-start gap-3 p-4 border-2 cursor-pointer transition-all duration-200 has-[:checked]:border-gold has-[:checked]:bg-gold/5 border-stone-200 dark:border-stone-800"
                                               style="border-radius: 4px;">
                                            <input type="radio" name="address_id" value="{{ $address->id }}"
                                                   class="mt-1 w-4 h-4 address-radio text-gold focus:ring-gold focus:ring-offset-0"
                                                   {{ $loop->first ? 'checked' : '' }}>
                                            <div class="text-sm flex-1">
                                                <p class="font-display font-bold mb-1.5">{{ $address->city->name }}</p>
                                                <p class="text-ink-muted dark:text-cream/60 mb-1">{{ $address->street_address }}</p>
                                                <p class="text-xs text-ink-faint dark:text-cream/40">{{ $address->phone }}</p>

                                                <div class="mt-3">
                                                    @if($address->city->hasFreeShipping())
                                                        <span class="inline-flex items-center gap-1.5 text-xs font-medium text-green-600 dark:text-green-400">
                                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                                                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                                            </svg>
                                                            شحن مجاني
                                                        </span>
                                                    @else
                                                        <span class="text-xs text-ink-muted dark:text-cream/60">
                                                            الشحن: ${{ number_format($address->city->shipping_cost, 2) }}
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                        </label>
                                    @endforeach
                                </div>

                                {{-- New Address Option --}}
                                <label class="flex items-center gap-3 p-4 border-2 cursor-pointer transition-all duration-200 has-[:checked]:border-gold has-[:checked]:bg-gold/5 border-stone-200 dark:border-stone-800"
                                       style="border-radius: 4px;">
                                    <input type="radio" name="address_option" value="new"
                                           class="w-4 h-4 text-gold focus:ring-gold focus:ring-offset-0">
                                    <span class="text-sm font-semibold">إضافة عنوان جديد</span>
                                </label>
                            </div>
                        @else
                            <input type="hidden" name="address_option" value="new">
                        @endif

                        {{-- New Address Form --}}
                        <div id="newAddressForm" class="{{ $addresses->count() ? 'hidden' : '' }} space-y-5 {{ $addresses->count() ? 'pt-6 border-t border-stone-200 dark:border-stone-800 mt-6' : '' }}">

                            <div>
                                <label class="block text-[10px] font-semibold tracking-widest uppercase text-ink-muted dark:text-cream/60 mb-3">
                                    المدينة
                                </label>
                                <select name="city_id" id="citySelect"
                                        class="w-full h-12 px-4 text-sm bg-transparent border border-stone-300 dark:border-stone-700 focus:border-forest dark:focus:border-gold focus:outline-none text-ink dark:text-cream transition-colors"
                                        style="border-radius: 4px;">
                                    <option value="">اختر المدينة</option>
                                    @foreach($cities as $city)
                                        <option value="{{ $city->id }}"
                                                data-shipping="{{ $city->hasFreeShipping() ? 0 : $city->shipping_cost }}">
                                            {{ $city->name }}
                                            @if($city->hasFreeShipping())
                                                — شحن مجاني
                                            @else
                                                — شحن ${{ number_format($city->shipping_cost, 2) }}
                                            @endif
                                        </option>
                                    @endforeach
                                </select>
                                @error('city_id') <p class="text-xs mt-2 text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="block text-[10px] font-semibold tracking-widest uppercase text-ink-muted dark:text-cream/60 mb-3">
                                    العنوان التفصيلي
                                </label>
                                <input type="text" name="street_address"
                                       placeholder="الحي، الشارع، رقم المبنى"
                                       class="w-full h-12 px-4 text-sm bg-transparent border border-stone-300 dark:border-stone-700 focus:border-forest dark:focus:border-gold focus:outline-none text-ink dark:text-cream placeholder:text-ink-faint dark:placeholder:text-cream/30 transition-colors"
                                       style="border-radius: 4px;">
                                @error('street_address') <p class="text-xs mt-2 text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="block text-[10px] font-semibold tracking-widest uppercase text-ink-muted dark:text-cream/60 mb-3">
                                    رقم الهاتف
                                </label>
                                <input type="tel" name="phone"
                                       placeholder="0599123456"
                                       dir="ltr"
                                       class="w-full h-12 px-4 text-sm bg-transparent border border-stone-300 dark:border-stone-700 focus:border-forest dark:focus:border-gold focus:outline-none text-ink dark:text-cream placeholder:text-ink-faint dark:placeholder:text-cream/30 transition-colors"
                                       style="border-radius: 4px;">
                                @error('phone') <p class="text-xs mt-2 text-red-600 dark:text-red-400">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="block text-[10px] font-semibold tracking-widest uppercase text-ink-muted dark:text-cream/60 mb-3">
                                    ملاحظات <span class="normal-case tracking-normal opacity-60">(اختياري)</span>
                                </label>
                                <textarea name="notes" rows="3"
                                          placeholder="أي تفاصيل إضافية تساعد المندوب..."
                                          class="w-full px-4 py-3 text-sm bg-transparent border border-stone-300 dark:border-stone-700 focus:border-forest dark:focus:border-gold focus:outline-none text-ink dark:text-cream placeholder:text-ink-faint dark:placeholder:text-cream/30 transition-colors resize-none"
                                          style="border-radius: 4px;"></textarea>
                            </div>
                        </div>
                    </div>
                </section>

                {{-- ─── ORDER ITEMS ─── --}}
                <section class="border border-stone-200 dark:border-stone-800" style="border-radius: 4px;">
                    <div class="flex items-center justify-between px-6 py-5 border-b border-stone-200 dark:border-stone-800">
                        <div class="flex items-center gap-3">
                            <span class="w-7 h-7 flex items-center justify-center bg-forest dark:bg-gold text-cream dark:text-ink font-display font-bold text-xs" style="border-radius: 4px;">02</span>
                            <span class="text-[10px] font-semibold tracking-widest uppercase text-ink-muted dark:text-cream/60">
                                المنتجات
                            </span>
                        </div>
                        <span class="text-xs font-bold text-forest dark:text-gold">
                            {{ count($items) }}
                        </span>
                    </div>

                    <div class="divide-y divide-stone-200 dark:divide-stone-800">
                        @foreach($items as $item)
                            <div class="p-5 flex items-center gap-4">
                                <div class="w-16 h-16 shrink-0 overflow-hidden bg-cream-warm dark:bg-zinc-900" style="border-radius: 4px;">
                                    @if($item['product']->images->first())
                                        <img src="{{ asset('storage/' . $item['product']->images->first()->image_path) }}"
                                             alt="{{ $item['product']->name }}"
                                             class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center text-ink-faint dark:text-cream/30">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                            </svg>
                                        </div>
                                    @endif
                                </div>

                                <div class="flex-1 min-w-0">
                                    <p class="font-display text-sm font-bold mb-1 truncate">{{ $item['product']->name }}</p>
                                    @if($item['variant']->color || $item['variant']->size)
                                        <p class="text-xs text-ink-muted dark:text-cream/50">
                                            {{ $item['variant']->color ?? '' }}{{ ($item['variant']->color && $item['variant']->size) ? ' · ' : '' }}{{ $item['variant']->size ?? '' }}
                                        </p>
                                    @endif
                                </div>

                                <div class="text-left shrink-0">
                                    <p class="font-display text-sm font-bold text-forest dark:text-gold">
                                        ${{ number_format(($item['product']->discount_price ?? $item['product']->price) * $item['quantity'], 2) }}
                                    </p>
                                    <p class="text-xs text-ink-muted dark:text-cream/50 mt-0.5">
                                        {{ $item['quantity'] }} × ${{ number_format($item['product']->discount_price ?? $item['product']->price, 2) }}
                                    </p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </section>

                {{-- Back to cart --}}
                <a href="{{ route('cart.index') }}" class="link-arrow inline-flex">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                    </svg>
                    العودة للسلة
                </a>
            </div>

            {{-- ════════════ SUMMARY ════════════ --}}
            <div class="lg:col-span-5">
                <div class="sticky top-24 border border-stone-200 dark:border-stone-800 p-8" style="border-radius: 4px;">

                    <span class="eyebrow block mb-6">— ملخص الطلب</span>

                    <div class="space-y-4 pb-6 mb-6 border-b border-stone-200 dark:border-stone-800">
                        <div class="flex justify-between text-sm">
                            <span class="text-ink-muted dark:text-cream/60">المجموع الفرعي</span>
                            <span class="font-medium">${{ number_format($total, 2) }}</span>
                        </div>

                        @if($discount > 0 && $coupon)
                            <div class="flex justify-between text-sm">
                                <span class="text-ink-muted dark:text-cream/60">الخصم ({{ $coupon->code }})</span>
                                <span class="font-medium text-green-600 dark:text-green-400">−${{ number_format($discount, 2) }}</span>
                            </div>
                        @endif

                        <div class="flex justify-between text-sm">
                            <span class="text-ink-muted dark:text-cream/60">الشحن</span>
                            <span id="shippingDisplay">
                                @if($shipping > 0)
                                    <span class="font-medium">${{ number_format($shipping, 2) }}</span>
                                @elseif($selectedCityId ?? null)
                                    <span class="font-medium text-green-600 dark:text-green-400">مجاني</span>
                                @else
                                    <span class="text-xs text-ink-faint dark:text-cream/40">يُحدد لاحقاً</span>
                                @endif
                            </span>
                        </div>
                    </div>

                    <div class="flex items-baseline justify-between mb-8">
                        <span class="font-display text-base font-bold">الإجمالي</span>
                        <span id="grandTotalDisplay" class="font-display text-3xl font-bold text-forest dark:text-gold">
                            ${{ number_format($grandTotal, 2) }}
                        </span>
                    </div>

                    <button type="submit" id="submitBtn"
                            class="group flex items-center justify-center gap-3 w-full h-14 font-display font-bold text-sm tracking-widest uppercase text-white bg-forest dark:bg-gold dark:text-ink hover:bg-gold dark:hover:bg-forest dark:hover:text-cream disabled:opacity-60 disabled:cursor-not-allowed transition-all"
                            style="border-radius: 4px;">
                        <span id="submitBtnText">تأكيد الطلب</span>
                        <svg class="w-4 h-4 transition-transform group-hover:-translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                        </svg>
                    </button>

                    {{-- Trust --}}
                    <div class="mt-8 pt-6 border-t border-stone-200 dark:border-stone-800 space-y-3">
                        @foreach([
                            ['title' => 'دفع آمن 100%', 'path' => 'M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z'],
                            ['title' => 'إرجاع مجاني خلال 14 يوم', 'path' => 'M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15'],
                            ['title' => 'دعم على مدار الساعة', 'path' => 'M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z'],
                        ] as $trust)
                            <div class="flex items-center gap-3 text-xs text-ink-muted dark:text-cream/60">
                                <svg class="w-4 h-4 text-forest dark:text-gold shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="{{ $trust['path'] }}" />
                                </svg>
                                <span>{{ $trust['title'] }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
    const CSRF_TOKEN = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    const FINAL_TOTAL = {{ $finalTotal }};

    document.querySelectorAll('input[name="address_option"]').forEach(radio => {
        radio.addEventListener('change', function () {
            const form = document.getElementById('newAddressForm');
            const shippingEl = document.getElementById('shippingDisplay');
            const totalEl = document.getElementById('grandTotalDisplay');

            if (this.value === 'new') {
                form.classList.remove('hidden');
                shippingEl.innerHTML = '<span class="text-xs text-ink-faint dark:text-cream/40">يُحدد لاحقاً</span>';
                totalEl.textContent = '$' + FINAL_TOTAL.toFixed(2);
            } else {
                form.classList.add('hidden');
                const selectedAddr = document.querySelector('input[name="address_id"]:checked');
                if (selectedAddr) updateShipping(selectedAddr.value);
            }
        });
    });

    document.querySelectorAll('input[name="address_id"]').forEach(radio => {
        radio.addEventListener('change', function () {
            updateShipping(this.value);
        });
    });

    document.getElementById('citySelect')?.addEventListener('change', function () {
        const selected = this.options[this.selectedIndex];
        const shipping = parseFloat(selected.dataset.shipping) || 0;
        const grandTotal = FINAL_TOTAL + shipping;
        updateShippingUI(shipping, grandTotal);
    });

    async function updateShipping(addressId) {
        try {
            const response = await fetch('{{ route('checkout.shipping') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': CSRF_TOKEN,
                    'Accept': 'application/json',
                },
                body: JSON.stringify({ address_id: addressId }),
            });
            const data = await response.json();
            updateShippingUI(data.shipping, data.grand_total);
        } catch (error) {
            console.error('Shipping error:', error);
        }
    }

    function updateShippingUI(shipping, grandTotal) {
        const shippingEl = document.getElementById('shippingDisplay');
        const totalEl = document.getElementById('grandTotalDisplay');

        if (shipping > 0) {
            shippingEl.innerHTML = '<span class="font-medium">$' + shipping.toFixed(2) + '</span>';
        } else {
            shippingEl.innerHTML = '<span class="font-medium text-green-600 dark:text-green-400">مجاني</span>';
        }

        if (grandTotal !== null && grandTotal !== undefined) {
            totalEl.textContent = '$' + grandTotal.toFixed(2);
        }
    }

    document.getElementById('checkoutForm').addEventListener('submit', function () {
        const btn = document.getElementById('submitBtn');
        const text = document.getElementById('submitBtnText');
        btn.disabled = true;
        text.textContent = 'جارٍ المعالجة...';
    });

    document.addEventListener('DOMContentLoaded', function () {
        const selectedAddr = document.querySelector('input[name="address_id"]:checked');
        if (selectedAddr) updateShipping(selectedAddr.value);
    });
</script>

@endsection