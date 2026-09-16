<x-guest-layout>

    <div class="mb-6">
        <h1 class="text-xl font-bold mb-2" style="color: var(--text-primary);">نسيت كلمة المرور؟</h1>
        <p class="text-[13px] leading-relaxed" style="color: var(--text-secondary);">
            لا مشكلة. أدخل بريدك الإلكتروني وسنرسل لك رابط إعادة تعيين كلمة المرور.
        </p>
    </div>

    @if(session('status'))
        <div class="px-4 py-3 rounded-lg mb-5 text-[13px]"
            style="background-color: #f0fdf4; color: #166534; border: 1px solid #bbf7d0;">
            {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('password.email') }}" class="space-y-5">
        @csrf

        <div>
            <label for="email" class="block text-[12px] font-semibold mb-2" style="color: var(--text-primary);">
                البريد الإلكتروني
            </label>

            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                autocomplete="username" placeholder="example@email.com"
                class="w-full h-11 rounded-lg px-3 text-[13px] focus:outline-none focus:ring-2 transition-all duration-150"
                style="background-color: var(--bg-primary); border: 1px solid var(--border-light); color: var(--text-primary); --tw-ring-color: var(--gold);">

            @error('email')
                <p class="text-[11px] mt-1.5" style="color: #dc2626;">{{ $message }}</p>
            @enderror
        </div>

        <button type="submit"
            class="w-full h-11 rounded-lg font-semibold text-[13px] text-white transition-all duration-200 hover:opacity-90"
            style="background-color: var(--gold);">
            إرسال رابط إعادة التعيين
        </button>

        <div class="text-center pt-2">
            <a href="{{ route('login') }}" class="text-[13px] font-medium transition-colors duration-150"
                style="color: var(--gold);" onmouseover="this.style.opacity='0.8';"
                onmouseout="this.style.opacity='1';">
                العودة إلى تسجيل الدخول
            </a>
        </div>

    </form>

</x-guest-layout>