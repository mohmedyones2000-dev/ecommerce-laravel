<x-guest-layout>

    <div class="mb-6">
        <h1 class="text-xl font-bold mb-1.5" style="color: var(--text-primary);">إنشاء حساب جديد</h1>
        <p class="text-[13px]" style="color: var(--text-secondary);">انضم إلينا وابدأ التسوق الآن</p>
    </div>

    @if ($errors->any())
        <div class="px-4 py-3 rounded-lg mb-5 text-[13px]"
            style="background-color: #fef2f2; color: #991b1b; border: 1px solid #fecaca;">
            @foreach ($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif

    <form method="POST" action="{{ route('register') }}" class="space-y-4">
        @csrf

        <div>
            <label for="name" class="block text-[12px] font-semibold mb-2" style="color: var(--text-primary);">
                الاسم الكامل
            </label>
            <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name"
                placeholder="أحمد محمد"
                class="w-full h-11 rounded-lg px-3 text-[13px] focus:outline-none focus:ring-2 transition-all duration-150"
                style="background-color: var(--bg-primary); border: 1px solid var(--border-light); color: var(--text-primary); --tw-ring-color: var(--gold);">
        </div>

        <div>
            <label for="email" class="block text-[12px] font-semibold mb-2" style="color: var(--text-primary);">
                البريد الإلكتروني
            </label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="username"
                placeholder="example@email.com"
                class="w-full h-11 rounded-lg px-3 text-[13px] focus:outline-none focus:ring-2 transition-all duration-150"
                style="background-color: var(--bg-primary); border: 1px solid var(--border-light); color: var(--text-primary); --tw-ring-color: var(--gold);">
        </div>

        <div>
            <label for="phone" class="block text-[12px] font-semibold mb-2" style="color: var(--text-primary);">
                رقم الهاتف <span class="font-normal" style="color: var(--text-tertiary);">(اختياري)</span>
            </label>
            <input id="phone" type="text" name="phone" value="{{ old('phone') }}" autocomplete="tel"
                placeholder="0599123456" dir="ltr"
                class="w-full h-11 rounded-lg px-3 text-[13px] text-right focus:outline-none focus:ring-2 transition-all duration-150"
                style="background-color: var(--bg-primary); border: 1px solid var(--border-light); color: var(--text-primary); --tw-ring-color: var(--gold);">
        </div>

        <div>
            <label for="password" class="block text-[12px] font-semibold mb-2" style="color: var(--text-primary);">
                كلمة المرور
            </label>
            <input id="password" type="password" name="password" required autocomplete="new-password"
                placeholder="••••••••"
                class="w-full h-11 rounded-lg px-3 text-[13px] focus:outline-none focus:ring-2 transition-all duration-150"
                style="background-color: var(--bg-primary); border: 1px solid var(--border-light); color: var(--text-primary); --tw-ring-color: var(--gold);">
        </div>

        <div>
            <label for="password_confirmation" class="block text-[12px] font-semibold mb-2"
                style="color: var(--text-primary);">
                تأكيد كلمة المرور
            </label>
            <input id="password_confirmation" type="password" name="password_confirmation" required
                autocomplete="new-password" placeholder="••••••••"
                class="w-full h-11 rounded-lg px-3 text-[13px] focus:outline-none focus:ring-2 transition-all duration-150"
                style="background-color: var(--bg-primary); border: 1px solid var(--border-light); color: var(--text-primary); --tw-ring-color: var(--gold);">
        </div>

        <label for="terms" class="flex items-start gap-2.5 cursor-pointer pt-1">
            <input id="terms" type="checkbox" required class="mt-0.5 w-4 h-4 rounded border-gray-300"
                style="accent-color: var(--gold);">
            <span class="text-[12px] leading-relaxed" style="color: var(--text-secondary);">
                أوافق على
                <a href="{{ route('pages.terms') }}" class="font-medium transition-colors duration-150"
                    style="color: var(--gold);" onmouseover="this.style.opacity='0.8';"
                    onmouseout="this.style.opacity='1';">
                    الشروط والأحكام
                </a>
                و
                <a href="{{ route('pages.privacy') }}" class="font-medium transition-colors duration-150"
                    style="color: var(--gold);" onmouseover="this.style.opacity='0.8';"
                    onmouseout="this.style.opacity='1';">
                    سياسة الخصوصية
                </a>
            </span>
        </label>

        <button type="submit"
            class="w-full h-11 rounded-lg font-semibold text-[13px] text-white transition-all duration-200 hover:opacity-90 mt-2"
            style="background-color: var(--gold);">
            إنشاء الحساب
        </button>

    </form>

    <div class="mt-6 pt-5 border-t text-center" style="border-color: var(--border-light);">
        <p class="text-[13px]" style="color: var(--text-secondary);">
            لديك حساب بالفعل؟
            <a href="{{ route('login') }}" class="font-semibold transition-colors duration-150"
                style="color: var(--gold);" onmouseover="this.style.opacity='0.8';"
                onmouseout="this.style.opacity='1';">
                تسجيل الدخول
            </a>
        </p>
    </div>

</x-guest-layout>