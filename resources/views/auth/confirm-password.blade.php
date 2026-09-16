<x-guest-layout>

    <div class="mb-6">
        <h1 class="text-xl font-bold mb-2" style="color: var(--text-primary);">تأكيد كلمة المرور</h1>
        <p class="text-[13px] leading-relaxed" style="color: var(--text-secondary);">
            هذه منطقة آمنة. الرجاء تأكيد كلمة المرور قبل المتابعة.
        </p>
    </div>

    <form method="POST" action="{{ route('password.confirm') }}" class="space-y-5">
        @csrf

        <div>
            <label for="password" class="block text-[12px] font-semibold mb-2" style="color: var(--text-primary);">
                كلمة المرور
            </label>

            <input id="password" type="password" name="password" required autocomplete="current-password"
                placeholder="••••••••"
                class="w-full h-11 rounded-lg px-3 text-[13px] focus:outline-none focus:ring-2 transition-all duration-150"
                style="background-color: var(--bg-primary); border: 1px solid var(--border-light); color: var(--text-primary); --tw-ring-color: var(--gold);">

            @error('password')
                <p class="text-[11px] mt-1.5" style="color: #dc2626;">{{ $message }}</p>
            @enderror
        </div>

        <button type="submit"
            class="w-full h-11 rounded-lg font-semibold text-[13px] text-white transition-all duration-200 hover:opacity-90"
            style="background-color: var(--gold);">
            تأكيد
        </button>

    </form>

</x-guest-layout>