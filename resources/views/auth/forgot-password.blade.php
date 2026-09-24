<x-guest-layout>

    {{-- ════════════ HEADER ════════════ --}}
    <header class="mb-10">
        <span class="eyebrow block mb-4">— نسيت كلمة المرور؟</span>

        <h1 class="display-2 mb-4 text-balance">
            استعادة
            <span class="text-forest dark:text-gold">الحساب</span>
        </h1>

        <p class="text-sm text-ink-muted dark:text-cream/60 text-pretty leading-relaxed">
            لا مشكلة. أدخل بريدك الإلكتروني وسنرسل لك رابط إعادة تعيين كلمة المرور.
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
            <ul class="space-y-1">
                @foreach ($errors->all() as $error)
                    <li class="text-xs leading-relaxed">{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- ════════════ FORM ════════════ --}}
    <form method="POST" action="{{ route('password.email') }}" class="space-y-6">
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

        {{-- Submit --}}
        <button type="submit"
            class="group flex items-center justify-center gap-3 w-full h-14 font-display font-bold text-sm tracking-widest uppercase text-white bg-forest dark:bg-gold dark:text-ink hover:bg-gold dark:hover:bg-forest dark:hover:text-cream transition-all"
            style="border-radius: 4px;">
            إرسال رابط الاستعادة
            <svg class="w-4 h-4 transition-transform group-hover:-translate-x-1" fill="none" stroke="currentColor"
                viewBox="0 0 24 24" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
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