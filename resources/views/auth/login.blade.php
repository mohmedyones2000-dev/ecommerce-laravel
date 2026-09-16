<x-guest-layout>

    <div class="mb-6">
        <h1 class="text-xl font-bold mb-1.5" style="color: var(--text-primary);">مرحباً بعودتك</h1>
        <p class="text-[13px]" style="color: var(--text-secondary);">سجّل دخولك لمتابعة التسوق</p>
    </div>

    @if ($errors->any())
        <div class="px-4 py-3 rounded-lg mb-5 text-[13px]"
             style="background-color: #fef2f2; color: #991b1b; border: 1px solid #fecaca;">
            @foreach ($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif

    @if (session('status'))
        <div class="px-4 py-3 rounded-lg mb-5 text-[13px]"
             style="background-color: #f0fdf4; color: #166534; border: 1px solid #bbf7d0;">
            {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}" class="space-y-5">
        @csrf

        <div>
            <label for="email" class="block text-[12px] font-semibold mb-2" style="color: var(--text-primary);">
                البريد الإلكتروني
            </label>
            <input id="email"
                   type="email"
                   name="email"
                   value="{{ old('email') }}"
                   required
                   autofocus
                   autocomplete="username"
                   placeholder="example@email.com"
                   class="w-full h-11 rounded-lg px-3 text-[13px] focus:outline-none focus:ring-2 transition-all duration-150"
                   style="background-color: var(--bg-primary); border: 1px solid var(--border-light); color: var(--text-primary); --tw-ring-color: var(--gold);">
        </div>

        <div>
            <label for="password" class="block text-[12px] font-semibold mb-2" style="color: var(--text-primary);">
                كلمة المرور
            </label>
            <input id="password"
                   type="password"
                   name="password"
                   required
                   autocomplete="current-password"
                   placeholder="••••••••"
                   class="w-full h-11 rounded-lg px-3 text-[13px] focus:outline-none focus:ring-2 transition-all duration-150"
                   style="background-color: var(--bg-primary); border: 1px solid var(--border-light); color: var(--text-primary); --tw-ring-color: var(--gold);">
        </div>

        <div class="flex items-center justify-between text-[12px]">
            <label for="remember_me" class="inline-flex items-center gap-2 cursor-pointer">
                <input id="remember_me"
                       type="checkbox"
                       name="remember"
                       class="w-4 h-4 rounded border-gray-300"
                       style="accent-color: var(--gold);">
                <span style="color: var(--text-secondary);">تذكرني</span>
            </label>

            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}"
                   class="font-medium transition-colors duration-150"
                   style="color: var(--gold);"
                   onmouseover="this.style.opacity='0.8';"
                   onmouseout="this.style.opacity='1';">
                    نسيت كلمة المرور؟
                </a>
            @endif
        </div>

        <button type="submit"
                class="w-full h-11 rounded-lg font-semibold text-[13px] text-white transition-all duration-200 hover:opacity-90"
                style="background-color: var(--gold);">
            تسجيل الدخول
        </button>

    </form>

    <div class="mt-6 pt-5 border-t text-center" style="border-color: var(--border-light);">
        <p class="text-[13px]" style="color: var(--text-secondary);">
            ليس لديك حساب؟
            <a href="{{ route('register') }}"
               class="font-semibold transition-colors duration-150"
               style="color: var(--gold);"
               onmouseover="this.style.opacity='0.8';"
               onmouseout="this.style.opacity='1';">
                إنشاء حساب جديد
            </a>
        </p>
    </div>

</x-guest-layout>