<x-guest-layout>

    {{-- ════════════ HEADER ════════════ --}}
    <header class="mb-10">
        <span class="eyebrow block mb-4">— مرحباً بعودتك</span>

        <h1 class="display-2 mb-4 text-balance">
            تسجيل
            <span class="text-forest dark:text-gold">الدخول</span>
        </h1>

        <p class="text-sm text-ink-muted dark:text-cream/60 text-pretty">
            سجّل دخولك لمتابعة التسوق والوصول إلى طلباتك.
        </p>
    </header>

    {{-- ════════════ SESSION STATUS ════════════ --}}
    @if (session('status'))
        <div class="mb-6 px-5 py-4 text-sm border border-green-200 dark:border-green-900/50 bg-green-50 dark:bg-green-950/20 text-green-800 dark:text-green-300 flex items-start gap-3"
            style="border-radius: 4px;">
            <svg class="w-5 h-5 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span>{{ session('status') }}</span>
        </div>
    @endif

    {{-- ════════════ ERRORS ════════════ --}}
    @if ($errors->any())
        <div class="mb-6 px-5 py-4 text-sm border border-red-200 dark:border-red-900/50 bg-red-50 dark:bg-red-950/20 text-red-800 dark:text-red-300"
            style="border-radius: 4px;">
            <p class="font-bold mb-2 flex items-center gap-2">
                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
                تعذر تسجيل الدخول
            </p>
            <ul class="space-y-1 pr-6">
                @foreach ($errors->all() as $error)
                    <li class="text-xs leading-relaxed">{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- ════════════ FORM ════════════ --}}
    <form method="POST" action="{{ route('login') }}" class="space-y-6">
        @csrf

        {{-- Email --}}
        <div>
            <label for="email"
                class="block text-[10px] font-semibold tracking-widest uppercase text-ink-muted dark:text-cream/60 mb-3">
                البريد الإلكتروني
            </label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                autocomplete="username" placeholder="example@email.com" dir="ltr"
                class="w-full h-12 px-4 text-sm text-left bg-transparent border border-stone-300 dark:border-stone-700 focus:border-forest dark:focus:border-gold focus:outline-none text-ink dark:text-cream placeholder:text-ink-faint dark:placeholder:text-cream/30 transition-colors"
                style="border-radius: 4px;">
        </div>

        {{-- Password --}}
        <div>
            <div class="flex items-center justify-between mb-3">
                <label for="password"
                    class="block text-[10px] font-semibold tracking-widest uppercase text-ink-muted dark:text-cream/60">
                    كلمة المرور
                </label>
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}"
                        class="text-xs font-semibold text-forest dark:text-gold hover:opacity-70 transition-opacity">
                        نسيت كلمة المرور؟
                    </a>
                @endif
            </div>
            <input id="password" type="password" name="password" required autocomplete="current-password"
                placeholder="••••••••"
                class="w-full h-12 px-4 text-sm bg-transparent border border-stone-300 dark:border-stone-700 focus:border-forest dark:focus:border-gold focus:outline-none text-ink dark:text-cream placeholder:text-ink-faint dark:placeholder:text-cream/30 transition-colors"
                style="border-radius: 4px;">
        </div>

        {{-- Remember --}}
        <label for="remember_me" class="inline-flex items-center gap-3 cursor-pointer group">
            <input id="remember_me" type="checkbox" name="remember"
                class="w-4 h-4 rounded-none border-stone-300 dark:border-stone-700 text-forest dark:text-gold focus:ring-forest dark:focus:ring-gold focus:ring-offset-0"
                style="border-radius: 2px;">
            <span
                class="text-sm text-ink-muted dark:text-cream/60 group-hover:text-ink dark:group-hover:text-cream transition-colors">
                تذكّرني في المرة القادمة
            </span>
        </label>

        {{-- Submit --}}
        <button type="submit"
            class="group flex items-center justify-center gap-3 w-full h-14 font-display font-bold text-sm tracking-widest uppercase text-white bg-forest dark:bg-gold dark:text-ink hover:bg-gold dark:hover:bg-forest dark:hover:text-cream transition-all"
            style="border-radius: 4px;">
            تسجيل الدخول
            <svg class="w-4 h-4 transition-transform group-hover:-translate-x-1" fill="none" stroke="currentColor"
                viewBox="0 0 24 24" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
            </svg>
        </button>
    </form>

    {{-- ════════════ SIGN UP LINK ════════════ --}}
    <div class="mt-10 pt-8 border-t border-stone-200 dark:border-stone-800 text-center">
        <p class="text-sm text-ink-muted dark:text-cream/60">
            ليس لديك حساب؟
            <a href="{{ route('register') }}"
                class="font-bold text-forest dark:text-gold hover:opacity-70 transition-opacity mr-1">
                إنشاء حساب جديد
            </a>
        </p>
    </div>

</x-guest-layout>