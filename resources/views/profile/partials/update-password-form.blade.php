<section>
    <form method="post" action="{{ route('password.update') }}" class="space-y-6">
        @csrf
        @method('put')

        {{-- Current Password --}}
        <div>
            <label for="update_password_current_password"
                class="block text-[10px] font-semibold tracking-widest uppercase text-ink-muted dark:text-cream/60 mb-3">
                كلمة المرور الحالية
            </label>
            <input id="update_password_current_password" name="current_password" type="password"
                autocomplete="current-password" placeholder="••••••••"
                class="w-full h-12 px-4 text-sm bg-transparent border border-stone-300 dark:border-stone-700 focus:border-forest dark:focus:border-gold focus:outline-none text-ink dark:text-cream placeholder:text-ink-faint dark:placeholder:text-cream/30 transition-colors"
                style="border-radius: 4px;">
            @error('current_password', 'updatePassword')
                <p class="text-xs mt-2 text-red-600 dark:text-red-400">{{ $message }}</p>
            @enderror
        </div>

        {{-- New Password --}}
        <div>
            <label for="update_password_password"
                class="block text-[10px] font-semibold tracking-widest uppercase text-ink-muted dark:text-cream/60 mb-3">
                كلمة المرور الجديدة
            </label>
            <input id="update_password_password" name="password" type="password" autocomplete="new-password"
                placeholder="••••••••"
                class="w-full h-12 px-4 text-sm bg-transparent border border-stone-300 dark:border-stone-700 focus:border-forest dark:focus:border-gold focus:outline-none text-ink dark:text-cream placeholder:text-ink-faint dark:placeholder:text-cream/30 transition-colors"
                style="border-radius: 4px;">
            @error('password', 'updatePassword')
                <p class="text-xs mt-2 text-red-600 dark:text-red-400">{{ $message }}</p>
            @enderror
        </div>

        {{-- Confirm Password --}}
        <div>
            <label for="update_password_password_confirmation"
                class="block text-[10px] font-semibold tracking-widest uppercase text-ink-muted dark:text-cream/60 mb-3">
                تأكيد كلمة المرور الجديدة
            </label>
            <input id="update_password_password_confirmation" name="password_confirmation" type="password"
                autocomplete="new-password" placeholder="••••••••"
                class="w-full h-12 px-4 text-sm bg-transparent border border-stone-300 dark:border-stone-700 focus:border-forest dark:focus:border-gold focus:outline-none text-ink dark:text-cream placeholder:text-ink-faint dark:placeholder:text-cream/30 transition-colors"
                style="border-radius: 4px;">
            @error('password_confirmation', 'updatePassword')
                <p class="text-xs mt-2 text-red-600 dark:text-red-400">{{ $message }}</p>
            @enderror
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
                استخدم كلمة مرور طويلة تحتوي على أحرف كبيرة وصغيرة وأرقام ورموز لأمان أعلى.
            </p>
        </div>

        {{-- Actions --}}
        <div class="flex flex-wrap items-center gap-4 pt-6 border-t border-stone-200 dark:border-stone-800">
            <button type="submit"
                class="group inline-flex items-center gap-3 h-12 px-7 font-display font-bold text-xs tracking-widest uppercase text-white bg-forest dark:bg-gold dark:text-ink hover:bg-gold dark:hover:bg-forest dark:hover:text-cream transition-all"
                style="border-radius: 4px;">
                تحديث كلمة المرور
                <svg class="w-4 h-4 transition-transform group-hover:-translate-x-1" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                </svg>
            </button>

            @if (session('status') === 'password-updated')
                <div class="flex items-center gap-2 text-sm font-medium text-green-600 dark:text-green-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                    </svg>
                    تم التحديث بنجاح
                </div>
            @endif
        </div>
    </form>
</section>