@extends('layouts.app')

@section('title', 'تم الطلب بنجاح | متجري')

@section('content')

    <div class="container-narrow pt-16 lg:pt-24 pb-24">

        {{-- ════════════ SUCCESS ICON ════════════ --}}
        <div class="text-center mb-14">

            <div class="inline-flex items-center justify-center w-24 h-24 mb-8 border-2 border-forest dark:border-gold" style="border-radius: 4px;">
                <svg class="w-12 h-12 text-forest dark:text-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                </svg>
            </div>

            <span class="eyebrow block mb-4">— تم بنجاح</span>

            <h1 class="display-2 mb-6 text-balance">
                شكراً لك<br>
                <span class="text-forest dark:text-gold">على طلبك</span>
            </h1>

            <p class="text-base text-ink-muted dark:text-cream/60 max-w-md mx-auto text-pretty leading-relaxed">
                استلمنا طلبك بنجاح، وسنتواصل معك قريباً لتأكيد التفاصيل.
            </p>
        </div>

        {{-- ════════════ ORDER DETAILS ════════════ --}}
        <div class="border border-stone-200 dark:border-stone-800 mb-10" style="border-radius: 4px;">

            <div class="px-8 py-6 border-b border-stone-200 dark:border-stone-800">
                <span class="text-[10px] font-semibold tracking-widest uppercase text-ink-muted dark:text-cream/60">
                    تفاصيل الطلب
                </span>
            </div>

            <div class="divide-y divide-stone-200 dark:divide-stone-800">

                {{-- Order number --}}
                <div class="flex items-center justify-between px-8 py-5">
                    <span class="text-sm text-ink-muted dark:text-cream/60">رقم الطلب</span>
                    <button type="button"
                            onclick="copyOrderNumber(this, '{{ $order->order_number }}')"
                            class="group inline-flex items-center gap-2 font-display font-bold text-sm text-ink dark:text-cream hover:text-forest dark:hover:text-gold transition-colors"
                            title="نسخ">
                        <span class="copy-number">{{ $order->order_number }}</span>
                        <svg class="w-3.5 h-3.5 opacity-40 group-hover:opacity-100 transition-opacity" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                        </svg>
                    </button>
                </div>

                {{-- Total --}}
                <div class="flex items-center justify-between px-8 py-5">
                    <span class="text-sm text-ink-muted dark:text-cream/60">الإجمالي</span>
                    <span class="font-display text-2xl font-bold text-forest dark:text-gold">
                        ${{ number_format($order->total_amount, 2) }}
                    </span>
                </div>

                {{-- Status --}}
                <div class="flex items-center justify-between px-8 py-5">
                    <span class="text-sm text-ink-muted dark:text-cream/60">الحالة</span>
                    @php
                        $statusMap = [
                            'pending' => ['label' => 'قيد المراجعة', 'color' => 'text-amber-600 dark:text-amber-400', 'bg' => 'bg-amber-50 dark:bg-amber-950/30', 'border' => 'border-amber-200 dark:border-amber-900/50'],
                            'processing' => ['label' => 'قيد المعالجة', 'color' => 'text-blue-600 dark:text-blue-400', 'bg' => 'bg-blue-50 dark:bg-blue-950/30', 'border' => 'border-blue-200 dark:border-blue-900/50'],
                            'shipped' => ['label' => 'تم الشحن', 'color' => 'text-forest dark:text-gold', 'bg' => 'bg-forest/10 dark:bg-gold/10', 'border' => 'border-forest/30 dark:border-gold/30'],
                            'delivered' => ['label' => 'تم التوصيل', 'color' => 'text-green-600 dark:text-green-400', 'bg' => 'bg-green-50 dark:bg-green-950/30', 'border' => 'border-green-200 dark:border-green-900/50'],
                            'cancelled' => ['label' => 'ملغي', 'color' => 'text-red-600 dark:text-red-400', 'bg' => 'bg-red-50 dark:bg-red-950/30', 'border' => 'border-red-200 dark:border-red-900/50'],
                        ];
                        $status = $statusMap[$order->status] ?? ['label' => $order->status, 'color' => 'text-ink-muted', 'bg' => 'bg-stone-100 dark:bg-stone-800', 'border' => 'border-stone-200 dark:border-stone-700'];
                    @endphp
                    <span class="inline-flex items-center gap-2 px-3 py-1.5 text-xs font-bold border {{ $status['bg'] }} {{ $status['border'] }} {{ $status['color'] }}" style="border-radius: 4px;">
                        <span class="w-1.5 h-1.5 rounded-full {{ str_replace('text-', 'bg-', $status['color']) }}"></span>
                        {{ $status['label'] }}
                    </span>
                </div>

                {{-- Date --}}
                @if($order->created_at)
                    <div class="flex items-center justify-between px-8 py-5">
                        <span class="text-sm text-ink-muted dark:text-cream/60">تاريخ الطلب</span>
                        <span class="text-sm text-ink dark:text-cream" dir="ltr">
                            {{ $order->created_at->format('Y-m-d · H:i') }}
                        </span>
                    </div>
                @endif
            </div>
        </div>

        {{-- ════════════ NEXT STEPS ════════════ --}}
        <div class="grid sm:grid-cols-3 gap-4 mb-14">
            @foreach([
                    ['n' => '01', 't' => 'تأكيد الطلب', 'd' => 'سنتواصل معك هاتفياً للتأكيد'],
                    ['n' => '02', 't' => 'التجهيز والشحن', 'd' => 'نحضّر طلبك بعناية ونشحنه'],
                    ['n' => '03', 't' => 'الاستلام', 'd' => 'يصل إلى باب منزلك'],
                ] as $step)
                    <div class="border border-stone-200 dark:border-stone-800 p-6" style="border-radius: 4px;">
                        <span class="font-display text-xs font-bold tracking-[0.3em] text-forest dark:text-gold block mb-3">
                            {{ $step['n'] }}
                        </span>
                        <p class="font-display font-bold text-sm mb-2">{{ $step['t'] }}</p>
                        <p class="text-xs text-ink-muted dark:text-cream/60 leading-relaxed">{{ $step['d'] }}</p>
                    </div>
            @endforeach
        </div>

        {{-- ════════════ ACTIONS ════════════ --}}
        <div class="flex flex-col sm:flex-row gap-3 justify-center">
            <a href="{{ route('orders.show', $order->id) }}" class="btn-solid group">
                عرض تفاصيل الطلب
                <svg class="w-4 h-4 transition-transform group-hover:-translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                </svg>
            </a>

            <a href="{{ route('products.index') }}" class="btn-outline">
                متابعة التسوق
            </a>
        </div>

        {{-- ════════════ CONTACT HELP ════════════ --}}
        @php $settings = \App\Models\SiteSetting::current(); @endphp
        @if($settings->whatsapp || $settings->phone)
            <div class="mt-14 pt-8 border-t border-stone-200 dark:border-stone-800 text-center">
                <p class="text-xs text-ink-muted dark:text-cream/50 mb-4">هل لديك أي استفسار؟</p>
                <div class="inline-flex items-center gap-4">
                    @if($settings->phone)
                        <a href="tel:{{ $settings->phone }}"
                           class="inline-flex items-center gap-2 text-xs font-semibold text-ink dark:text-cream hover:text-forest dark:hover:text-gold transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                            </svg>
                            <span dir="ltr">{{ $settings->phone }}</span>
                        </a>
                    @endif

                    @if($settings->whatsapp)
                        <a href="https://wa.me/{{ $settings->whatsapp }}?text={{ urlencode('مرحباً، لدي استفسار عن الطلب ' . $order->order_number) }}"
                           target="_blank" rel="noopener"
                           class="inline-flex items-center gap-2 text-xs font-semibold text-ink dark:text-cream hover:text-forest dark:hover:text-gold transition-colors">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                            </svg>
                            واتساب
                        </a>
                    @endif
                </div>
            </div>
        @endif
    </div>

    <script>
        function copyOrderNumber(button, orderNumber) {
            navigator.clipboard.writeText(orderNumber).then(() => {
                const span = button.querySelector('.copy-number');
                const original = span.textContent;
                span.textContent = 'تم النسخ';
                if (window.showToast) window.showToast('تم نسخ رقم الطلب', 'success');
                setTimeout(() => { span.textContent = original; }, 1500);
            }).catch(() => {
                if (window.showToast) window.showToast('فشل النسخ', 'error');
            });
        }
    </script>

@endsection