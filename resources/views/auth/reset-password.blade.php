<x-guest-layout>

    {{-- ════════════ HEADER ════════════ --}}
    <header class="mb-10">
        <span class="eyebrow block mb-4">— كلمة مرور جديدة</span>

        <h1 class="display-2 mb-4 text-balance">
            إعادة
            <span class="text-forest dark:text-gold">التعيين</span>
        </h1>

        <p class="text-sm text-ink-muted dark:text-cream/60 text-pretty">
            أدخل كلمة المرور الجديدة لحسابك. سنطبّقها فوراً بمجرد الحفظ.
        </p>
    </header>

    {{-- ════════════ ERRORS ════════════ --}}
    @if ($errors->any())
        <div class="mb-6 px-5 py-4 text-sm border border-red-200 dark:border-red-900/50 bg-red-50 dark:bg-red-950/20 text-red-800 dark:text-red-300"
            style="border-radius: 4px;">
            <p class="font-bold mb-2 flex items-center gap-2">
                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
                تعذر إعادة التعيين
            </p>
            <ul class="space-y-1 pr-6">
                @foreach ($errors->all() as $error)
                    <li class="text-xs leading-relaxed">{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- ════════════ FORM ════════════ --}}
    <form method="POST" action="{{ route('password.store') }}" class="space-y-6">
        @csrf

        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        {{-- Email --}}
        <div>
            <label for="email"
                class="block text-[10px] font-semibold tracking-widest uppercase text-ink-muted dark:text-cream/60 mb-3">
                البريد الإلكتروني
            </label>
            <input id="email" type="email" name="email" value="{{ old('email', $request->email) }}" required autofocus
                autocomplete="username" placeholder="example@email.com" dir="ltr" readonly
                class="w-full h-12 px-4 text-sm text-left bg-stone-50 dark:bg-zinc-900 border border-stone-300 dark:border-stone-700 text-ink-muted dark:text-cream/60 cursor-not-allowed focus:outline-none transition-colors"
                style="border-radius: 4px;">
            <p class="text-xs mt-2 text-ink-faint dark:text-cream/40">
                هذا الحساب مرتبط بهذا البريد الإلكتروني.
            </p>
        </div>

        {{-- New Password --}}
        <div>
            <label for="password"
                class="block text-[10px] font-semibold tracking-widest uppercase text-ink-muted dark:text-cream/60 mb-3">
                كلمة المرور الجديدة
            </label>
            <input id="password" type="password" name="password" required autocomplete="new-password"
                placeholder="••••••••"
                class="w-full h-12 px-4 text-sm bg-transparent border border-stone-300 dark:border-stone-700 focus:border-forest dark:focus:border-gold focus:outline-none text-ink dark:text-cream placeholder:text-ink-faint dark:placeholder:text-cream/30 transition-colors"
                style="border-radius: 4px;">
        </div>

        {{-- Confirm Password --}}
        <div>
            <label for="password_confirmation"
                class="block text-[10px] font-semibold tracking-widest uppercase text-ink-muted dark:text-cream/60 mb-3">
                تأكيد كلمة المرور
            </label>
            <input id="password_confirmation" type="password" name="password_confirmation" required
                autocomplete="new-password" placeholder="••••••••"
                class="w-full h-12 px-4 text-sm bg-transparent border border-stone-300 dark:border-stone-700 focus:border-forest dark:focus:border-gold focus:outline-none text-ink dark:text-cream placeholder:text-ink-faint dark:placeholder:text-cream/30 transition-colors"
                style="border-radius: 4px;">
        </div>

        {{-- Hint --}}
        <div class="flex items-start gap-3 p-4 border border-stone-200 dark:border-stone-800 bg-stone-50 dark:bg-zinc-900"
            style="border-radius: 4px;">
            <svg class="w-4 h-4 shrink-0 mt-0.5 text-forest dark:text-gold" fill="none" stroke="currentColor"
                viewBox="0 0 24 24" stroke-width="1.8">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <p class="text-xs leading-relaxed text-ink-muted dark:text-cream/60">
                استخدم كلمة مرور قوية تحتوي على أحرف كبيرة وصغيرة وأرقام. الحد الأدنى 8 أحرف.
            </p>
        </div>

        {{-- Submit --}}
        <button type="submit"
            class="group flex items-center justify-center gap-3 w-full h-14 font-display font-bold text-sm tracking-widest uppercase text-white bg-forest dark:bg-gold dark:text-ink hover:bg-gold dark:hover:bg-forest dark:hover:text-cream transition-all"
            style="border-radius: 4px;">
            إعادة تعيين كلمة المرور
            <svg class="w-4 h-4 transition-transform group-hover:-translate-x-1" fill="none" stroke="currentColor"
                viewBox="0 0 24 24" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
            </svg>
        </button>
    </form>

    {{-- ════════════ BACK TO LOGIN ════════════ --}}
    <div class="mt-10 pt-8 border-t border-stone-200 dark:border-stone-800 text-center">
        <p class="text-sm text-ink-muted dark:text-cream/60">
            تذكرت كلمة المرور؟
            <a href="{{ route('login') }}"
                class="font-bold text-forest dark:text-gold hover:opacity-70 transition-opacity mr-1">
                العودة لتسجيل الدخول
            </a>
        </p>
    </div>

</x-guest-layout>