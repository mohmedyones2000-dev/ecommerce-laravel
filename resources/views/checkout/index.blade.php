@extends('layouts.app')

@section('title', 'إتمام الطلب | متجري')

@section('content')

<div class="max-w-[1400px] mx-auto px-4 sm:px-6 lg:px-8 py-8">

    <div class="mb-8">
        <h1 class="text-2xl md:text-3xl font-bold mb-1.5" style="color: var(--text-primary);">إتمام الطلب</h1>
        <p class="text-[13px]" style="color: var(--text-secondary);">أكمل بياناتك لإتمام عملية الشراء</p>
    </div>

    @if(session('error'))
        <div class="px-4 py-3 rounded-lg mb-5 text-[13px]" style="background-color: #fef2f2; color: #991b1b; border: 1px solid #fecaca;">
            {{ session('error') }}
        </div>
    @endif

    <form action="{{ route('checkout.store') }}" method="POST" id="checkoutForm">
        @csrf

        <div class="grid md:grid-cols-3 gap-6">

            <div class="md:col-span-2 space-y-5">

                <div class="rounded-xl border overflow-hidden" style="background-color: var(--bg-primary); border-color: var(--border-light);">
                    <div class="px-5 py-4 border-b" style="border-color: var(--border-light);">
                        <h2 class="text-[14px] font-semibold" style="color: var(--text-primary);">عنوان الشحن</h2>
                    </div>

                    <div class="p-5">

                        @if($addresses->count())
                            <div class="space-y-3 mb-4">

                                <label class="flex items-start gap-3 p-3.5 rounded-lg border-2 cursor-pointer transition-all duration-150"
                                       style="border-color: var(--border-light);"
                                       onmouseover="this.style.borderColor='var(--gold)';"
                                       onmouseout="if(!this.querySelector('input').checked) this.style.borderColor='var(--border-light)';">
                                    <input type="radio" name="address_option" value="existing" checked
                                           class="mt-1 w-4 h-4" style="accent-color: var(--gold);">
                                    <span class="text-[13px] font-semibold" style="color: var(--text-primary);">استخدام عنوان محفوظ</span>
                                </label>

                                <div class="space-y-2 pr-6">
                                    @foreach($addresses as $address)
                                        <label class="flex items-start gap-3 p-3 rounded-lg border cursor-pointer transition-all duration-150"
                                               style="border-color: var(--border-light); background-color: var(--bg-secondary);"
                                               onmouseover="this.style.borderColor='var(--gold)';"
                                               onmouseout="if(!this.querySelector('input').checked) this.style.borderColor='var(--border-light)';">
                                            <input type="radio" name="address_id" value="{{ $address->id }}"
                                                   class="mt-1 w-4 h-4 address-radio" style="accent-color: var(--gold);"
                                                   {{ $loop->first ? 'checked' : '' }}>
                                            <div class="text-[12px] flex-1">
                                                <p class="font-semibold mb-0.5" style="color: var(--text-primary);">{{ $address->city->name }}</p>
                                                <p class="mb-0.5" style="color: var(--text-secondary);">{{ $address->street_address }}</p>
                                                <p style="color: var(--text-tertiary);">{{ $address->phone }}</p>

                                                <div class="mt-1.5">
                                                    @if($address->city->hasFreeShipping())
                                                        <span class="inline-flex items-center gap-1 text-[11px] font-medium" style="color: #166534;">
                                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                                            </svg>
                                                            شحن مجاني
                                                        </span>
                                                    @else
                                                        <span class="text-[11px]" style="color: var(--text-tertiary);">
                                                            الشحن: ${{ number_format($address->city->shipping_cost, 2) }}
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                        </label>
                                    @endforeach
                                </div>

                                <label class="flex items-center gap-3 p-3.5 rounded-lg border-2 cursor-pointer transition-all duration-150"
                                       style="border-color: var(--border-light);"
                                       onmouseover="this.style.borderColor='var(--gold)';"
                                       onmouseout="if(!this.querySelector('input').checked) this.style.borderColor='var(--border-light)';">
                                    <input type="radio" name="address_option" value="new"
                                           class="w-4 h-4" style="accent-color: var(--gold);">
                                    <span class="text-[13px] font-semibold" style="color: var(--text-primary);">إضافة عنوان جديد</span>
                                </label>
                            </div>
                        @else
                            <input type="hidden" name="address_option" value="new">
                        @endif

                        <div id="newAddressForm" class="{{ $addresses->count() ? 'hidden' : '' }} space-y-4 {{ $addresses->count() ? 'pt-4 border-t mt-4' : '' }}" style="border-color: var(--border-light);">
                            <div>
                                <label class="block text-[12px] font-semibold mb-2" style="color: var(--text-primary);">المدينة</label>
                                <select name="city_id" id="citySelect"
                                        class="w-full h-11 rounded-lg px-3 text-[13px] focus:outline-none focus:ring-2 transition-all duration-150"
                                        style="background-color: var(--bg-primary); border: 1px solid var(--border-light); color: var(--text-primary); --tw-ring-color: var(--gold);">
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
                                @error('city_id') <p class="text-[11px] mt-1" style="color: #dc2626;">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="block text-[12px] font-semibold mb-2" style="color: var(--text-primary);">العنوان التفصيلي</label>
                                <input type="text" name="street_address"
                                       placeholder="الحي، الشارع، رقم المبنى"
                                       class="w-full h-11 rounded-lg px-3 text-[13px] focus:outline-none focus:ring-2 transition-all duration-150"
                                       style="background-color: var(--bg-primary); border: 1px solid var(--border-light); color: var(--text-primary); --tw-ring-color: var(--gold);">
                                @error('street_address') <p class="text-[11px] mt-1" style="color: #dc2626;">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="block text-[12px] font-semibold mb-2" style="color: var(--text-primary);">رقم الهاتف</label>
                                <input type="text" name="phone"
                                       placeholder="0599123456"
                                       class="w-full h-11 rounded-lg px-3 text-[13px] focus:outline-none focus:ring-2 transition-all duration-150"
                                       style="background-color: var(--bg-primary); border: 1px solid var(--border-light); color: var(--text-primary); --tw-ring-color: var(--gold);">
                                @error('phone') <p class="text-[11px] mt-1" style="color: #dc2626;">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="block text-[12px] font-semibold mb-2" style="color: var(--text-primary);">
                                    ملاحظات <span style="color: var(--text-tertiary); font-weight: 400;">(اختياري)</span>
                                </label>
                                <textarea name="notes" rows="2"
                                          placeholder="أي تفاصيل إضافية"
                                          class="w-full rounded-lg py-2.5 px-3 text-[13px] focus:outline-none focus:ring-2 transition-all duration-150"
                                          style="background-color: var(--bg-primary); border: 1px solid var(--border-light); color: var(--text-primary); --tw-ring-color: var(--gold);"></textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="rounded-xl border overflow-hidden" style="background-color: var(--bg-primary); border-color: var(--border-light);">
                    <div class="px-5 py-4 border-b" style="border-color: var(--border-light);">
                        <h2 class="text-[14px] font-semibold" style="color: var(--text-primary);">
                            المنتجات ({{ count($items) }})
                        </h2>
                    </div>

                    <div class="divide-y" style="border-color: var(--border-light);">
                        @foreach($items as $item)
                            <div class="p-4 flex items-center gap-3">
                                <div class="w-14 h-14 rounded-lg overflow-hidden shrink-0" style="background-color: var(--bg-tertiary);">
                                    @if($item['product']->images->first())
                                        <img src="{{ asset('storage/' . $item['product']->images->first()->image_path) }}"
                                             alt="{{ $item['product']->name }}"
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
                                    <p class="text-[13px] font-medium truncate mb-0.5" style="color: var(--text-primary);">{{ $item['product']->name }}</p>
                                    @if($item['variant']->color || $item['variant']->size)
                                        <p class="text-[11px]" style="color: var(--text-tertiary);">
                                            {{ $item['variant']->color ?? '' }}{{ ($item['variant']->color && $item['variant']->size) ? ' · ' : '' }}{{ $item['variant']->size ?? '' }}
                                        </p>
                                    @endif
                                </div>

                                <div class="text-[12px] text-left" style="color: var(--text-secondary);">
                                    <span class="font-semibold">{{ $item['quantity'] }}</span> ×
                                    ${{ number_format($item['product']->discount_price ?? $item['product']->price, 2) }}
                                </div>
                            </div>
                        @endforeach
                    </div>
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

                        @if($discount > 0 && $coupon)
                            <div class="flex justify-between" style="color: #166534;">
                                <span>الخصم ({{ $coupon->code }})</span>
                                <span>-${{ number_format($discount, 2) }}</span>
                            </div>
                        @endif

                        <div class="flex justify-between" style="color: var(--text-secondary);">
                            <span>الشحن</span>
                            <span id="shippingDisplay">
                                @if($shipping > 0)
                                    <span style="color: var(--text-primary);">${{ number_format($shipping, 2) }}</span>
                                @elseif($selectedCityId ?? null)
                                    <span style="color: #166534;">مجاني</span>
                                @else
                                    <span style="color: var(--text-tertiary);">—</span>
                                @endif
                            </span>
                        </div>
                    </div>

                    <div class="my-4 border-t" style="border-color: var(--border-light);"></div>

                    <div class="flex justify-between items-baseline mb-5">
                        <span class="text-[14px] font-bold" style="color: var(--text-primary);">الإجمالي</span>
                        <span id="grandTotalDisplay" class="text-xl font-bold" style="color: var(--gold);">
                            ${{ number_format($grandTotal, 2) }}
                        </span>
                    </div>

                    <button type="submit" id="submitBtn"
                            class="w-full h-11 rounded-lg font-semibold text-[13px] text-white transition-all duration-200 hover:opacity-90 disabled:opacity-60 disabled:cursor-not-allowed"
                            style="background-color: var(--gold);">
                        <span id="submitBtnText">تأكيد الطلب</span>
                    </button>

                    <a href="{{ route('cart.index') }}"
                       class="block w-full h-11 rounded-lg font-semibold text-[13px] text-center leading-[44px] mt-2 transition-all duration-150"
                       style="background-color: var(--bg-tertiary); color: var(--text-primary);"
                       onmouseover="this.style.backgroundColor='var(--gold-soft)'; this.style.color='var(--gold)';"
                       onmouseout="this.style.backgroundColor='var(--bg-tertiary)'; this.style.color='var(--text-primary)';">
                        العودة للسلة
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
                shippingEl.innerHTML = '<span style="color: var(--text-tertiary);">—</span>';
                totalEl.textContent = '$' + FINAL_TOTAL.toFixed(2);
            } else {
                form.classList.add('hidden');
                const selectedAddr = document.querySelector('input[name="address_id"]:checked');
                if (selectedAddr) {
                    updateShipping(selectedAddr.value);
                }
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
            shippingEl.innerHTML = '<span style="color: var(--text-primary);">$' + shipping.toFixed(2) + '</span>';
        } else {
            shippingEl.innerHTML = '<span style="color: #166534;">مجاني</span>';
        }

        if (grandTotal !== null && grandTotal !== undefined) {
            totalEl.textContent = '$' + grandTotal.toFixed(2);
        }
    }

    document.getElementById('checkoutForm').addEventListener('submit', function () {
        const btn = document.getElementById('submitBtn');
        const text = document.getElementById('submitBtnText');

        btn.disabled = true;
        text.textContent = 'جاري معالجة الطلب...';
    });

    document.addEventListener('DOMContentLoaded', function () {
        const selectedAddr = document.querySelector('input[name="address_id"]:checked');
        if (selectedAddr) {
            updateShipping(selectedAddr.value);
        }
    });
</script>

@endsection