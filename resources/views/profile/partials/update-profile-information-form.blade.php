<section>
    <form method="post" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="space-y-8">
        @csrf
        @method('patch')

        {{-- ════════════ AVATAR ════════════ --}}
        <div class="flex flex-col sm:flex-row items-center sm:items-start gap-6 pb-8 border-b border-stone-200 dark:border-stone-800">

            <div class="relative group shrink-0">
                @if($user->avatar)
                    <img id="avatarPreview"
                         src="{{ asset('storage/' . $user->avatar) }}"
                         alt="{{ $user->name }}"
                         class="w-24 h-24 object-cover ring-2 ring-stone-200 dark:ring-stone-800 group-hover:ring-forest dark:group-hover:ring-gold transition-all"
                         style="border-radius: 4px;">
                @else
                    <div id="avatarPreviewContainer"
                         class="w-24 h-24 flex items-center justify-center text-white text-3xl font-display font-bold bg-forest dark:bg-gold dark:text-ink ring-2 ring-stone-200 dark:ring-stone-800 group-hover:ring-gold transition-all"
                         style="border-radius: 4px;">
                        {{ $user->initial }}
                    </div>
                    <img id="avatarPreview"
                         src=""
                         alt="Preview"
                         class="hidden w-24 h-24 object-cover ring-2 ring-forest dark:ring-gold"
                         style="border-radius: 4px;">
                @endif

                <label for="avatar"
                       class="absolute -bottom-1 -left-1 w-8 h-8 flex items-center justify-center cursor-pointer bg-forest dark:bg-gold text-cream dark:text-ink shadow-lg hover:scale-110 transition-transform"
                       style="border-radius: 4px;"
                       title="تغيير الصورة">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                </label>

                <input type="file" id="avatar" name="avatar" accept="image/*" class="hidden">
            </div>

            <div class="flex-1 text-center sm:text-right pt-2">
                <p class="text-[10px] font-semibold tracking-widest uppercase text-ink-muted dark:text-cream/60 mb-2">
                    الصورة الشخصية
                </p>
                <p class="text-xs text-ink-faint dark:text-cream/40 mb-4 leading-relaxed">
                    JPG، PNG، أو WEBP — الحد الأقصى 2 ميجابايت.
                </p>

                <label for="avatar"
                       class="inline-flex items-center gap-2 h-10 px-4 font-display font-bold text-[10px] tracking-widest uppercase border border-stone-300 dark:border-stone-700 hover:border-forest dark:hover:border-gold hover:text-forest dark:hover:text-gold cursor-pointer transition-colors"
                       style="border-radius: 4px;">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                    </svg>
                    اختر صورة
                </label>
            </div>
        </div>

        {{-- Avatar Error --}}
        @if ($errors->has('avatar'))
            <div class="px-5 py-4 text-sm border border-red-200 dark:border-red-900/50 bg-red-50 dark:bg-red-950/20 text-red-800 dark:text-red-300" style="border-radius: 4px;">
                {{ $errors->first('avatar') }}
            </div>
        @endif

        {{-- ════════════ FIELDS ════════════ --}}
        <div class="grid sm:grid-cols-2 gap-6">

            {{-- Name --}}
            <div>
                <label for="name" class="block text-[10px] font-semibold tracking-widest uppercase text-ink-muted dark:text-cream/60 mb-3">
                    الاسم
                </label>
                <input id="name"
                       type="text"
                       name="name"
                       value="{{ old('name', $user->name) }}"
                       required
                       autocomplete="name"
                       class="w-full h-12 px-4 text-sm bg-transparent border border-stone-300 dark:border-stone-700 focus:border-forest dark:focus:border-gold focus:outline-none text-ink dark:text-cream transition-colors"
                       style="border-radius: 4px;">
                @error('name')
                    <p class="text-xs mt-2 text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            {{-- Phone --}}
            <div>
                <label for="phone" class="block text-[10px] font-semibold tracking-widest uppercase text-ink-muted dark:text-cream/60 mb-3">
                    رقم الهاتف
                </label>
                <input id="phone"
                       type="tel"
                       name="phone"
                       value="{{ old('phone', $user->phone) }}"
                       placeholder="0599123456"
                       dir="ltr"
                       class="w-full h-12 px-4 text-sm text-left bg-transparent border border-stone-300 dark:border-stone-700 focus:border-forest dark:focus:border-gold focus:outline-none text-ink dark:text-cream placeholder:text-ink-faint dark:placeholder:text-cream/30 transition-colors"
                       style="border-radius: 4px;">
                @error('phone')
                    <p class="text-xs mt-2 text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>
        </div>

        {{-- Email --}}
        <div>
            <label for="email" class="block text-[10px] font-semibold tracking-widest uppercase text-ink-muted dark:text-cream/60 mb-3">
                البريد الإلكتروني
            </label>
            <input id="email"
                   type="email"
                   name="email"
                   value="{{ old('email', $user->email) }}"
                   required
                   autocomplete="username"
                   dir="ltr"
                   class="w-full h-12 px-4 text-sm text-left bg-transparent border border-stone-300 dark:border-stone-700 focus:border-forest dark:focus:border-gold focus:outline-none text-ink dark:text-cream transition-colors"
                   style="border-radius: 4px;">
            @error('email')
                <p class="text-xs mt-2 text-red-600 dark:text-red-400">{{ $message }}</p>
            @enderror

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && !$user->hasVerifiedEmail())
                <div class="mt-4 p-4 border border-amber-200 dark:border-amber-900/50 bg-amber-50 dark:bg-amber-950/20" style="border-radius: 4px;">
                    <p class="text-sm text-amber-800 dark:text-amber-300 mb-2">
                        بريدك الإلكتروني غير موثق.
                    </p>
                    <button form="send-verification"
                            class="text-xs font-semibold text-amber-800 dark:text-amber-300 underline underline-offset-4 hover:opacity-70 transition-opacity">
                        اضغط هنا لإعادة إرسال رابط التوثيق
                    </button>

                    @if (session('status') === 'verification-link-sent')
                        <p class="text-xs mt-3 font-medium text-green-600 dark:text-green-400">
                            تم إرسال رابط توثيق جديد إلى بريدك الإلكتروني.
                        </p>
                    @endif
                </div>
            @endif
        </div>

        {{-- ════════════ ACTIONS ════════════ --}}
        <div class="flex flex-wrap items-center gap-4 pt-6 border-t border-stone-200 dark:border-stone-800">
            <button type="submit"
                    class="group inline-flex items-center gap-3 h-12 px-7 font-display font-bold text-xs tracking-widest uppercase text-white bg-forest dark:bg-gold dark:text-ink hover:bg-gold dark:hover:bg-forest dark:hover:text-cream transition-all"
                    style="border-radius: 4px;">
                حفظ التغييرات
                <svg class="w-4 h-4 transition-transform group-hover:-translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                </svg>
            </button>

            @if (session('status') === 'profile-updated')
                <div class="flex items-center gap-2 text-sm font-medium text-green-600 dark:text-green-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                    </svg>
                    تم الحفظ بنجاح
                </div>
            @endif
        </div>
    </form>

    {{-- Hidden Verification Form --}}
    <form id="send-verification" method="post" action="{{ route('verification.send') }}" class="hidden">
        @csrf
    </form>

    {{-- ════════════ AVATAR PREVIEW JS ════════════ --}}
    <script>
        document.getElementById('avatar')?.addEventListener('change', function (e) {
            const file = e.target.files[0];
            if (!file) return;

            const reader = new FileReader();
            reader.onload = function (ev) {
                const preview = document.getElementById('avatarPreview');
                const container = document.getElementById('avatarPreviewContainer');

                preview.src = ev.target.result;
                preview.classList.remove('hidden');

                if (container) container.classList.add('hidden');
            };
            reader.readAsDataURL(file);
        });
    </script>
</section>