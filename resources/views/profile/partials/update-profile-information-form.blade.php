<section>
    <header class="mb-5">
        <h2 class="text-[15px] font-bold mb-1.5 flex items-center gap-2" style="color: var(--text-primary);">
            <svg class="w-4 h-4" style="color: var(--gold);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
            </svg>
            المعلومات الشخصية
        </h2>
        <p class="text-[12px]" style="color: var(--text-secondary);">
            حدّث اسمك وبريدك الإلكتروني ورقم هاتفك.
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}" class="hidden">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="space-y-4">
        @csrf
        @method('patch')

        <div>
            <label for="name" class="block text-[12px] font-semibold mb-2" style="color: var(--text-primary);">
                الاسم الكامل
            </label>
            <input id="name" name="name" type="text" value="{{ old('name', $user->name) }}" required autofocus
                autocomplete="name"
                class="w-full h-11 rounded-lg px-3 text-[13px] focus:outline-none focus:ring-2 transition-all duration-150"
                style="background-color: var(--bg-primary); border: 1px solid var(--border-light); color: var(--text-primary); --tw-ring-color: var(--gold);">
            @error('name')
                <p class="text-[11px] mt-1" style="color: #dc2626;">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="email" class="block text-[12px] font-semibold mb-2" style="color: var(--text-primary);">
                البريد الإلكتروني
            </label>
            <input id="email" name="email" type="email" value="{{ old('email', $user->email) }}" required
                autocomplete="username"
                class="w-full h-11 rounded-lg px-3 text-[13px] focus:outline-none focus:ring-2 transition-all duration-150"
                style="background-color: var(--bg-primary); border: 1px solid var(--border-light); color: var(--text-primary); --tw-ring-color: var(--gold);">
            @error('email')
                <p class="text-[11px] mt-1" style="color: #dc2626;">{{ $message }}</p>
            @enderror

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && !$user->hasVerifiedEmail())
                <div class="mt-3 rounded-lg p-3 flex items-start gap-2.5"
                    style="background-color: #fef3c7; border: 1px solid #fde68a;">
                    <svg class="w-4 h-4 shrink-0 mt-0.5" style="color: #b45309;" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                    <div class="text-[12px] leading-relaxed" style="color: #92400e;">
                        بريدك الإلكتروني غير موثّق.
                        <button form="send-verification" class="font-semibold underline transition-opacity duration-150"
                            onmouseover="this.style.opacity='0.7';" onmouseout="this.style.opacity='1';">
                            إعادة إرسال رابط التحقق
                        </button>
                    </div>
                </div>
            @endif
        </div>

        <div>
            <label for="phone" class="block text-[12px] font-semibold mb-2" style="color: var(--text-primary);">
                رقم الهاتف
            </label>
            <input id="phone" name="phone" type="text" value="{{ old('phone', $user->phone) }}" placeholder="0599123456"
                class="w-full h-11 rounded-lg px-3 text-[13px] focus:outline-none focus:ring-2 transition-all duration-150"
                style="background-color: var(--bg-primary); border: 1px solid var(--border-light); color: var(--text-primary); --tw-ring-color: var(--gold);">
            @error('phone')
                <p class="text-[11px] mt-1" style="color: #dc2626;">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex items-center gap-3 pt-2">
            <button type="submit"
                class="h-10 px-5 rounded-lg font-semibold text-[12px] text-white transition-all duration-200 hover:opacity-90"
                style="background-color: var(--gold);">
                حفظ التغييرات
            </button>

            @if (session('status') === 'profile-updated')
                <span class="inline-flex items-center gap-1.5 text-[12px] font-medium" style="color: #166534;">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                    </svg>
                    تم الحفظ بنجاح
                </span>
            @endif
        </div>
    </form>
</section>