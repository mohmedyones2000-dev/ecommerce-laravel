<section>
    <header class="mb-5">
        <h2 class="text-[15px] font-bold mb-1.5 flex items-center gap-2" style="color: var(--text-primary);">
            <svg class="w-4 h-4" style="color: var(--gold);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
            </svg>
            تغيير كلمة المرور
        </h2>
        <p class="text-[12px]" style="color: var(--text-secondary);">
            تأكد من استخدام كلمة مرور قوية وآمنة.
        </p>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="space-y-4">
        @csrf
        @method('put')

        <div>
            <label for="update_password_current_password" class="block text-[12px] font-semibold mb-2" style="color: var(--text-primary);">
                كلمة المرور الحالية
            </label>
            <input id="update_password_current_password" name="current_password" type="password"
                   autocomplete="current-password" placeholder="••••••••"
                   class="w-full h-11 rounded-lg px-3 text-[13px] focus:outline-none focus:ring-2 transition-all duration-150"
                   style="background-color: var(--bg-primary); border: 1px solid var(--border-light); color: var(--text-primary); --tw-ring-color: var(--gold);">
            @error('current_password', 'updatePassword')
                <p class="text-[11px] mt-1" style="color: #dc2626;">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="update_password_password" class="block text-[12px] font-semibold mb-2" style="color: var(--text-primary);">
                كلمة المرور الجديدة
            </label>
            <input id="update_password_password" name="password" type="password"
                   autocomplete="new-password" placeholder="••••••••"
                   class="w-full h-11 rounded-lg px-3 text-[13px] focus:outline-none focus:ring-2 transition-all duration-150"
                   style="background-color: var(--bg-primary); border: 1px solid var(--border-light); color: var(--text-primary); --tw-ring-color: var(--gold);">
            @error('password', 'updatePassword')
                <p class="text-[11px] mt-1" style="color: #dc2626;">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="update_password_password_confirmation" class="block text-[12px] font-semibold mb-2" style="color: var(--text-primary);">
                تأكيد كلمة المرور الجديدة
            </label>
            <input id="update_password_password_confirmation" name="password_confirmation" type="password"
                   autocomplete="new-password" placeholder="••••••••"
                   class="w-full h-11 rounded-lg px-3 text-[13px] focus:outline-none focus:ring-2 transition-all duration-150"
                   style="background-color: var(--bg-primary); border: 1px solid var(--border-light); color: var(--text-primary); --tw-ring-color: var(--gold);">
            @error('password_confirmation', 'updatePassword')
                <p class="text-[11px] mt-1" style="color: #dc2626;">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex items-center gap-3 pt-2">
            <button type="submit"
                    class="h-10 px-5 rounded-lg font-semibold text-[12px] text-white transition-all duration-200 hover:opacity-90"
                    style="background-color: var(--gold);">
                تحديث كلمة المرور
            </button>

            @if (session('status') === 'password-updated')
                <span class="inline-flex items-center gap-1.5 text-[12px] font-medium" style="color: #166534;">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                    </svg>
                    تم التحديث بنجاح
                </span>
            @endif
        </div>
    </form>
</section>