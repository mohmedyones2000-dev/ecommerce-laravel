<x-guest-layout>

    {{-- ════════════ ICON ════════════ --}}
    <div class="mb-10">
        <div class="w-16 h-16 flex items-center justify-center bg-forest/5 dark:bg-gold/10 text-forest dark:text-gold mb-8"
            style="border-radius: 4px;">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
            </svg>
        </div>

        <span class="eyebrow block mb-4">— منطقة آمنة</span>

        <h1 class="display-2 mb-4 text-balance">
            تأكيد
            <span class="text-forest dark:text-gold">كلمة المرور</span>
        </h1>

        <p class="text-sm text-ink-muted dark:text-cream/60 text-pretty leading-relaxed">
            هذه منطقة محمية. الرجاء تأكيد كلمة المرور قبل المتابعة.
        </p>
    </div>

    {{-- ════════════ ERRORS ════════════ --}}
    @if ($errors->any())
        <div class="mb-6 px-5 py-4 text-sm border border-red-200 dark:border-red-900/50 bg-red-50 dark:bg-red-950/20 text-red-800 dark:text-red-300"
            style="border-radius: 4px;">
            <p class="font-bold mb-2 flex items-center gap-2">
                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
                تعذر التأكيد
            </p>
            <ul class="space-y-1 pr-6">
                @foreach ($errors->all() as $error)
                    <li class="text-xs leading-relaxed">{{ $error }}</li>
                @endforeach
            </ul>
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
            هذا الإجراء للحماية الإضافية. لن تحتاج لتكراره إلا عند تعديل بيانات حساسة.
        </p>
    </div>

    {{-- ════════════ FORM ════════════ --}}
    <form method="POST" action="{{ route('password.confirm') }}" class="space-y-6">
        @csrf

        {{-- Password --}}
        <div>
            <label for="password"
                class="block text-[10px] font-semibold tracking-widest uppercase text-ink-muted dark:text-cream/60 mb-3">
                كلمة المرور
            </label>
            <input id="password" type="password" name="password" required autofocus autocomplete="current-password"
                placeholder="••••••••"
                class="w-full h-12 px-4 text-sm bg-transparent border border-stone-300 dark:border-stone-700 focus:border-forest dark:focus:border-gold focus:outline-none text-ink dark:text-cream placeholder:text-ink-faint dark:placeholder:text-cream/30 transition-colors"
                style="border-radius: 4px;">
        </div>

        {{-- Submit --}}
        <button type="submit"
            class="group flex items-center justify-center gap-3 w-full h-14 font-display font-bold text-sm tracking-widest uppercase text-white bg-forest dark:bg-gold dark:text-ink hover:bg-gold dark:hover:bg-forest dark:hover:text-cream transition-all"
            style="border-radius: 4px;">
            تأكيد كلمة المرور
            <svg class="w-4 h-4 transition-transform group-hover:-translate-x-1" fill="none" stroke="currentColor"
                viewBox="0 0 24 24" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
            </svg>
        </button>
    </form>

    {{-- ════════════ BACK TO HOME ════════════ --}}
    <div class="mt-10 pt-8 border-t border-stone-200 dark:border-stone-800 text-center">
        <p class="text-sm text-ink-muted dark:text-cream/60">
            العودة إلى
            <a href="{{ url('/') }}"
                class="font-bold text-forest dark:text-gold hover:opacity-70 transition-opacity mr-1">
                الصفحة الرئيسية
            </a>
        </p>
    </div>

</x-guest-layout>