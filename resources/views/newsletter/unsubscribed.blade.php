@extends('layouts.app')

@section('title', 'تم إلغاء الاشتراك | متجري')

@section('content')

    <div class="container-narrow pt-16 lg:pt-24 pb-24">

        {{-- ════════════ ICON ════════════ --}}
        <div class="text-center mb-14">

            <div class="inline-flex items-center justify-center w-24 h-24 mb-8 border-2 border-stone-300 dark:border-stone-700"
                style="border-radius: 4px;">
                <svg class="w-12 h-12 text-ink-muted dark:text-cream/50" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                </svg>
            </div>

            <span class="eyebrow block mb-4">— النشرة البريدية</span>

            <h1 class="display-2 mb-6 text-balance">
                تم إلغاء
                <span class="text-forest dark:text-gold">اشتراكك</span>
            </h1>

            <p class="text-base text-ink-muted dark:text-cream/60 max-w-md mx-auto text-pretty leading-relaxed">
                نأسف لرؤيتك تذهب. تم إلغاء اشتراكك من النشرة البريدية بنجاح.
            </p>
        </div>

        {{-- ════════════ INFO BOX ════════════ --}}
        <div class="border border-stone-200 dark:border-stone-800 p-8 mb-10" style="border-radius: 4px;">
            <div class="flex items-start gap-4">
                <div class="w-10 h-10 flex items-center justify-center bg-forest/10 dark:bg-gold/10 text-forest dark:text-gold shrink-0"
                    style="border-radius: 4px;">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div>
                    <p class="font-display font-bold text-sm mb-2">هل غيّرت رأيك؟</p>
                    <p class="text-sm text-ink-muted dark:text-cream/60 leading-relaxed">
                        يمكنك دائماً الاشتراك مرة أخرى في أي وقت. سنكون سعداء بعودتك.
                    </p>
                </div>
            </div>
        </div>

        {{-- ════════════ RESUBSCRIBE FORM ════════════ --}}
        <div class="border border-stone-200 dark:border-stone-800 p-8 mb-10" style="border-radius: 4px;">
            <span class="text-[10px] font-semibold tracking-widest uppercase text-ink-muted dark:text-cream/60 block mb-4">
                اشترك من جديد
            </span>

            <form id="newsletterForm" onsubmit="return submitNewsletter(event)" class="flex flex-col sm:flex-row gap-3">
                <input type="email" id="newsletterEmail" required placeholder="بريدك الإلكتروني"
                    class="flex-1 h-12 px-4 text-sm bg-transparent border border-stone-300 dark:border-stone-700 focus:border-forest dark:focus:border-gold focus:outline-none text-ink dark:text-cream placeholder:text-ink-faint dark:placeholder:text-cream/30 transition-colors"
                    style="border-radius: 4px;">

                <button type="submit" id="newsletterBtn"
                    class="h-12 px-6 font-display font-bold text-xs tracking-widest uppercase text-white bg-forest dark:bg-gold dark:text-ink hover:bg-gold dark:hover:bg-forest dark:hover:text-cream disabled:opacity-60 disabled:cursor-not-allowed transition-all whitespace-nowrap flex items-center justify-center gap-2 shrink-0"
                    style="border-radius: 4px;">
                    <span id="newsletterBtnText">اشترك الآن</span>
                    <svg id="newsletterSpinner" class="hidden animate-spin h-3.5 w-3.5" xmlns="http://www.w3.org/2000/svg"
                        fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" />
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z" />
                    </svg>
                </button>
            </form>

            <div id="newsletterMessage" class="hidden mt-4 px-4 py-3 text-sm border" style="border-radius: 4px;"></div>
        </div>

        {{-- ════════════ ACTIONS ════════════ --}}
        <div class="flex flex-col sm:flex-row gap-3 justify-center pt-8 border-t border-stone-200 dark:border-stone-800">

            <a href="{{ route('products.index') }}" class="btn-solid group">
                تصفح المنتجات
                <svg class="w-4 h-4 transition-transform group-hover:-translate-x-1" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                </svg>
            </a>

            <a href="{{ route('home') }}" class="btn-outline">
                العودة للرئيسية
            </a>
        </div>

    </div>

    <script>
        async function submitNewsletter(event) {
            event.preventDefault();

            const emailInput = document.getElementById('newsletterEmail');
            const btn = document.getElementById('newsletterBtn');
            const btnText = document.getElementById('newsletterBtnText');
            const spinner = document.getElementById('newsletterSpinner');
            const msg = document.getElementById('newsletterMessage');
            const email = emailInput.value.trim();

            if (!email) return false;

            btn.disabled = true;
            btnText.textContent = 'جارٍ...';
            spinner?.classList.remove('hidden');
            msg?.classList.add('hidden');

            try {
                const res = await fetch('{{ route('newsletter.subscribe') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({ email }),
                });
                const data = await res.json();

                btn.disabled = false;
                btnText.textContent = 'اشترك الآن';
                spinner?.classList.add('hidden');

                if (msg) {
                    msg.className = 'mt-4 px-4 py-3 text-sm border ' + (data.success
                        ? 'bg-green-50 dark:bg-green-950/20 border-green-200 dark:border-green-900/50 text-green-800 dark:text-green-300'
                        : 'bg-red-50 dark:bg-red-950/20 border-red-200 dark:border-red-900/50 text-red-800 dark:text-red-300');
                    msg.textContent = data.message;
                    msg.classList.remove('hidden');
                    msg.style.borderRadius = '4px';
                }

                if (data.success) {
                    emailInput.value = '';
                    if (window.showToast) window.showToast(data.message, 'success');
                }
            } catch (e) {
                btn.disabled = false;
                btnText.textContent = 'اشترك الآن';
                spinner?.classList.add('hidden');
                if (window.showToast) window.showToast('حدث خطأ، حاول مرة أخرى', 'error');
            }
            return false;
        }
    </script>

@endsection