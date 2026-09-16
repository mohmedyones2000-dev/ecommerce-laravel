<x-guest-layout>

    <div class="mb-6 text-center">
        <div class="w-14 h-14 rounded-xl flex items-center justify-center mx-auto mb-4"
            style="background-color: var(--gold-soft);">
            <svg class="w-7 h-7" style="color: var(--gold);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                    d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
            </svg>
        </div>

        <h1 class="text-xl font-bold mb-2" style="color: var(--text-primary);">تحقق من بريدك الإلكتروني</h1>
        <p class="text-[13px] leading-relaxed" style="color: var(--text-secondary);">
            شكراً لتسجيلك. قبل البدء، يرجى تأكيد بريدك الإلكتروني من خلال الرابط الذي أرسلناه لك.
            إذا لم تستلم البريد، يمكننا إرسال رابط جديد.
        </p>
    </div>

    @if (session('status') == 'verification-link-sent')
        <div class="px-4 py-3 rounded-lg mb-5 text-[13px]"
            style="background-color: #f0fdf4; color: #166534; border: 1px solid #bbf7d0;">
            تم إرسال رابط تحقق جديد إلى بريدك الإلكتروني.
        </div>
    @endif

    <form method="POST" action="{{ route('verification.send') }}">
        @csrf

        <button type="submit"
            class="w-full h-11 rounded-lg font-semibold text-[13px] text-white transition-all duration-200 hover:opacity-90"
            style="background-color: var(--gold);">
            إعادة إرسال رابط التحقق
        </button>
    </form>

    <div class="mt-4 text-center">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="text-[12px] font-medium transition-colors duration-150"
                style="color: var(--text-tertiary);" onmouseover="this.style.color='#dc2626';"
                onmouseout="this.style.color='var(--text-tertiary)';">
                تسجيل الخروج
            </button>
        </form>
    </div>

</x-guest-layout>