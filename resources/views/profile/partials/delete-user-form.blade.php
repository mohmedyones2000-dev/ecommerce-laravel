<section>
    <header class="mb-5">
        <h2 class="text-[15px] font-bold mb-1.5 flex items-center gap-2" style="color: #dc2626;">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>
            حذف الحساب
        </h2>
        <p class="text-[12px]" style="color: var(--text-secondary);">
            عند حذف حسابك، سيتم حذف جميع بياناتك وطلباتك نهائياً. لا يمكن التراجع عن هذا الإجراء.
        </p>
    </header>

    <button type="button"
            onclick="document.getElementById('deleteAccountModal').classList.remove('hidden'); document.getElementById('deleteAccountModal').classList.add('flex');"
            class="h-10 px-5 rounded-lg font-semibold text-[12px] text-white transition-all duration-200 hover:opacity-90"
            style="background-color: #dc2626;">
        حذف الحساب نهائياً
    </button>

    <div id="deleteAccountModal"
         class="hidden fixed inset-0 bg-black/60 z-50 items-center justify-center p-4">

        <div class="rounded-xl max-w-md w-full p-6 shadow-2xl"
             style="background-color: var(--bg-primary);">

            <div class="flex items-start gap-3 mb-5">
                <div class="w-10 h-10 rounded-lg flex items-center justify-center shrink-0" style="background-color: #fee2e2;">
                    <svg class="w-5 h-5" style="color: #dc2626;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
                <div>
                    <h3 class="text-[15px] font-bold mb-1" style="color: var(--text-primary);">هل أنت متأكد؟</h3>
                    <p class="text-[12px] leading-relaxed" style="color: var(--text-secondary);">
                        هذا الإجراء لا يمكن التراجع عنه. أدخل كلمة المرور لتأكيد حذف الحساب.
                    </p>
                </div>
            </div>

            <form method="post" action="{{ route('profile.destroy') }}" class="space-y-4">
                @csrf
                @method('delete')

                <div>
                    <label for="password_delete" class="block text-[12px] font-semibold mb-2" style="color: var(--text-primary);">
                        كلمة المرور
                    </label>
                    <input id="password_delete" name="password" type="password"
                           placeholder="••••••••"
                           class="w-full h-11 rounded-lg px-3 text-[13px] focus:outline-none focus:ring-2 transition-all duration-150"
                           style="background-color: var(--bg-primary); border: 1px solid var(--border-light); color: var(--text-primary); --tw-ring-color: #dc2626;">
                    @error('password', 'userDeletion')
                        <p class="text-[11px] mt-1" style="color: #dc2626;">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex gap-2 justify-end pt-2">
                    <button type="button"
                            onclick="document.getElementById('deleteAccountModal').classList.add('hidden'); document.getElementById('deleteAccountModal').classList.remove('flex');"
                            class="h-10 px-5 rounded-lg font-semibold text-[12px] transition-all duration-150"
                            style="background-color: var(--bg-tertiary); color: var(--text-primary);"
                            onmouseover="this.style.backgroundColor='var(--border-light)';"
                            onmouseout="this.style.backgroundColor='var(--bg-tertiary)';">
                        إلغاء
                    </button>
                    <button type="submit"
                            class="h-10 px-5 rounded-lg font-semibold text-[12px] text-white transition-all duration-200 hover:opacity-90"
                            style="background-color: #dc2626;">
                        تأكيد الحذف
                    </button>
                </div>
            </form>
        </div>
    </div>
</section>