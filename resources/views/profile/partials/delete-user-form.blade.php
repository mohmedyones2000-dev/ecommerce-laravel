<section>
    {{-- Warning Info --}}
    <div class="flex items-start gap-4 p-5 border border-red-200 dark:border-red-900/50 bg-red-50 dark:bg-red-950/20 mb-8"
        style="border-radius: 4px;">
        <div class="w-10 h-10 flex items-center justify-center bg-red-600 text-white shrink-0"
            style="border-radius: 4px;">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>
        </div>
        <div>
            <p class="font-display font-bold text-sm text-red-800 dark:text-red-300 mb-1.5">
                تحذير — إجراء لا رجعة فيه
            </p>
            <p class="text-xs leading-relaxed text-red-700 dark:text-red-400">
                عند حذف حسابك، سيتم حذف جميع بياناتك وطلباتك وسجل تصفحك نهائياً. لا يمكن التراجع عن هذا الإجراء.
            </p>
        </div>
    </div>

    {{-- Delete Trigger --}}
    <button type="button"
        onclick="document.getElementById('deleteAccountModal').classList.remove('hidden'); document.getElementById('deleteAccountModal').classList.add('flex');"
        class="group inline-flex items-center gap-3 h-12 px-6 font-display font-bold text-xs tracking-widest uppercase text-white bg-red-600 hover:bg-red-700 transition-colors"
        style="border-radius: 4px;">
        حذف الحساب نهائياً
        <svg class="w-4 h-4 transition-transform group-hover:scale-110" fill="none" stroke="currentColor"
            viewBox="0 0 24 24" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round"
                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
        </svg>
    </button>

    {{-- ════════════ MODAL ════════════ --}}
    <div id="deleteAccountModal"
        class="hidden fixed inset-0 bg-black/70 dark:bg-black/80 backdrop-blur-sm z-[80] items-center justify-center p-4">

        <div class="max-w-md w-full bg-canvas dark:bg-zinc-950 shadow-2xl border border-stone-200 dark:border-stone-800"
            style="border-radius: 4px;" onclick="event.stopPropagation()">

            {{-- Modal Header --}}
            <div class="flex items-start gap-4 p-6 border-b border-stone-200 dark:border-stone-800">
                <div class="w-11 h-11 flex items-center justify-center bg-red-600 text-white shrink-0"
                    style="border-radius: 4px;">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
                <div class="flex-1">
                    <h3 class="font-display font-bold text-base text-ink dark:text-cream mb-1.5">
                        هل أنت متأكد؟
                    </h3>
                    <p class="text-xs leading-relaxed text-ink-muted dark:text-cream/60">
                        هذا الإجراء لا يمكن التراجع عنه. أدخل كلمة المرور لتأكيد حذف الحساب.
                    </p>
                </div>
            </div>

            {{-- Modal Body --}}
            <form method="post" action="{{ route('profile.destroy') }}" class="p-6 space-y-6">
                @csrf
                @method('delete')

                <div>
                    <label for="password_delete"
                        class="block text-[10px] font-semibold tracking-widest uppercase text-ink-muted dark:text-cream/60 mb-3">
                        كلمة المرور
                    </label>
                    <input id="password_delete" name="password" type="password" placeholder="••••••••"
                        autocomplete="current-password"
                        class="w-full h-12 px-4 text-sm bg-transparent border border-stone-300 dark:border-stone-700 focus:border-red-600 dark:focus:border-red-500 focus:outline-none text-ink dark:text-cream placeholder:text-ink-faint dark:placeholder:text-cream/30 transition-colors"
                        style="border-radius: 4px;">
                    @error('password', 'userDeletion')
                        <p class="text-xs mt-2 text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Actions --}}
                <div class="flex gap-3 justify-end pt-2">
                    <button type="button"
                        onclick="document.getElementById('deleteAccountModal').classList.add('hidden'); document.getElementById('deleteAccountModal').classList.remove('flex');"
                        class="inline-flex items-center h-11 px-6 font-display font-bold text-[10px] tracking-widest uppercase border border-stone-300 dark:border-stone-700 text-ink dark:text-cream hover:border-forest dark:hover:border-gold transition-colors"
                        style="border-radius: 4px;">
                        إلغاء
                    </button>
                    <button type="submit"
                        class="inline-flex items-center h-11 px-6 font-display font-bold text-[10px] tracking-widest uppercase text-white bg-red-600 hover:bg-red-700 transition-colors"
                        style="border-radius: 4px;">
                        تأكيد الحذف
                    </button>
                </div>
            </form>
        </div>
    </div>
</section>