<x-guest-layout>

    {{-- ════════════ ICON ════════════ --}}
    <div class="mb-10">
        <div class="w-16 h-16 flex items-center justify-center bg-forest/5 dark:bg-gold/10 text-forest dark:text-gold mb-8"
            style="border-radius: 4px;">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
            </svg>
        </div>

        <span class="eyebrow block mb-4">— تفعيل الحساب</span>

        <h1 class="display-2 mb-4 text-balance">
            تحقّق من
            <span class="text-forest dark:text-gold">بريدك</span>
        </h1>

        <p class="text-sm text-ink-muted dark:text-cream/60 text-pretty leading-relaxed">
            شكراً لتسجيلك. قبل البدء، يرجى تأكيد بريدك الإلكتروني من خلال الرابط الذي أرسلناه لك. إذا لم تستلم البريد،
            يمكننا إرسال رابط جديد.
        </p>
    </div>

    {{-- ════════════ STATUS ════════════ --}}
    @if (session('status') == 'verification-link-sent')
        <div class="mb-6 px-5 py-4 text-sm border border-green-200 dark:border-green-900/50 bg-green-50 dark:bg-green-950/20 text-green-800 dark:text-green-300 flex items-start gap-3"
            style="border-radius: 4px;">
            <svg class="w-5 h-5 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span>تم إرسال رابط تحقق جديد إلى بريدك الإلكتروني.</span>
        </div>
    @endif

    {{-- ════════════ INFO BOX ════════════ --}}
    <div class="mb-8 flex items-start gap-3 p-4 border border-stone-200 dark:border-stone-800 bg-stone-50 dark:bg-zinc-900"
        style="border-radius: 4px;">
        <svg class="w-4 h-4 shrink-0 mt-0.5 text-forest dark:text-gold" fill="none" stroke="currentColor"
            viewBox="0 0 24 24" stroke-width="1.8">
            <path stroke-linecap="round" stroke-linejoin="round"
                d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        <p class="text-xs leading-relaxed text-ink-muted dark:text-cream/60">
            تحقق من مجلد <span class="font-semibold">البريد العشوائي (Spam)</span> إذا لم يظهر البريد في صندوق الوارد
            خلال دقائق.
        </p>
    </div>

    {{-- ════════════ RESEND FORM ════════════ --}}
    <form method="POST" action="{{ route('verification.send') }}">
        @csrf

        <button type="submit"
            class="group flex items-center justify-center gap-3 w-full h-14 font-display font-bold text-sm tracking-widest uppercase text-white bg-forest dark:bg-gold dark:text-ink hover:bg-gold dark:hover:bg-forest dark:hover:text-cream transition-all"
            style="border-radius: 4px;">
            إعادة إرسال رابط التحقق
            <svg class="w-4 h-4 transition-transform group-hover:-translate-x-1" fill="none" stroke="currentColor"
                viewBox="0 0 24 24" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
            </svg>
        </button>
    </form>

    {{-- ════════════ LOGOUT ════════════ --}}
    <div class="mt-10 pt-8 border-t border-stone-200 dark:border-stone-800 text-center">
        <p class="text-sm text-ink-muted dark:text-cream/60 mb-4">
            هل ترغب باستخدام حساب آخر؟
        </p>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit"
                class="inline-flex items-center gap-2 text-xs font-semibold tracking-widest uppercase text-ink-faint dark:text-cream/40 hover:text-red-600 dark:hover:text-red-400 transition-colors">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                </svg>
                تسجيل الخروج
            </button>
        </form>
    </div>

</x-guest-layout>