<section>
    <header class="mb-6">
        <h2 class="text-[15px] font-bold mb-1" style="color: var(--text-primary);">المعلومات الشخصية</h2>
        <p class="text-[12px]" style="color: var(--text-secondary);">قم بتحديث صورتك الشخصية ومعلومات حسابك</p>
    </header>

    <form method="post" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="space-y-5">
        @csrf
        @method('patch')

        <div class="flex flex-col sm:flex-row items-center gap-5 pb-5 border-b"
            style="border-color: var(--border-light);">
            <div class="relative group">
                @if($user->avatar)
                    <img id="avatarPreview" src="{{ asset('storage/' . $user->avatar) }}" alt="{{ $user->name }}"
                        class="w-24 h-24 rounded-full object-cover border-4" style="border-color: var(--gold-soft);">
                @else
                    <div id="avatarPreviewContainer"
                        class="w-24 h-24 rounded-full flex items-center justify-center text-white text-3xl font-bold border-4"
                        style="background-color: var(--gold); border-color: var(--gold-soft);">
                        {{ $user->initial }}
                    </div>
                    <img id="avatarPreview" src="" alt="Preview" class="hidden w-24 h-24 rounded-full object-cover border-4"
                        style="border-color: var(--gold-soft);">
                @endif

                <label for="avatar"
                    class="absolute bottom-0 left-0 w-8 h-8 rounded-full flex items-center justify-center cursor-pointer transition-all duration-150"
                    style="background-color: var(--gold); color: white;" title="تغيير الصورة">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                </label>

                <input type="file" id="avatar" name="avatar" accept="image/*" class="hidden">
            </div>

            <div class="flex-1 text-center sm:text-right">
                <p class="text-[13px] font-semibold mb-1" style="color: var(--text-primary);">الصورة الشخصية</p>
                <p class="text-[11px] mb-2" style="color: var(--text-tertiary);">
                    JPG، PNG، أو WEBP. الحد الأقصى 2 ميجابايت.
                </p>
                <label for="avatar"
                    class="inline-flex items-center gap-2 h-9 px-4 rounded-lg text-[12px] font-semibold cursor-pointer transition-all duration-150"
                    style="background-color: var(--bg-tertiary); color: var(--text-primary);">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                    </svg>
                    اختر صورة
                </label>
            </div>
        </div>

        @if ($errors->has('avatar'))
            <div class="px-4 py-2.5 rounded-lg text-[12px]"
                style="background-color: #fef2f2; color: #991b1b; border: 1px solid #fecaca;">
                {{ $errors->first('avatar') }}
            </div>
        @endif

        <div class="grid sm:grid-cols-2 gap-4">
            <div>
                <label for="name" class="block text-[12px] font-semibold mb-2"
                    style="color: var(--text-primary);">الاسم</label>
                <input id="name" type="text" name="name" value="{{ old('name', $user->name) }}" required
                    autocomplete="name"
                    class="w-full h-11 rounded-lg px-3 text-[13px] focus:outline-none focus:ring-2 transition-all duration-150"
                    style="background-color: var(--bg-primary); border: 1px solid var(--border-light); color: var(--text-primary); --tw-ring-color: var(--gold);">
                @error('name')
                <p class="text-[11px] mt-1" style="color: #dc2626;">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="phone" class="block text-[12px] font-semibold mb-2" style="color: var(--text-primary);">رقم
                    الهاتف</label>
                <input id="phone" type="text" name="phone" value="{{ old('phone', $user->phone) }}"
                    placeholder="0599123456" dir="ltr"
                    class="w-full h-11 rounded-lg px-3 text-[13px] text-right focus:outline-none focus:ring-2 transition-all duration-150"
                    style="background-color: var(--bg-primary); border: 1px solid var(--border-light); color: var(--text-primary); --tw-ring-color: var(--gold);">
                @error('phone')
                <p class="text-[11px] mt-1" style="color: #dc2626;">{{ $message }}</p> @enderror
            </div>
        </div>

        <div>
            <label for="email" class="block text-[12px] font-semibold mb-2" style="color: var(--text-primary);">البريد
                الإلكتروني</label>
            <input id="email" type="email" name="email" value="{{ old('email', $user->email) }}" required
                autocomplete="username"
                class="w-full h-11 rounded-lg px-3 text-[13px] focus:outline-none focus:ring-2 transition-all duration-150"
                style="background-color: var(--bg-primary); border: 1px solid var(--border-light); color: var(--text-primary); --tw-ring-color: var(--gold);">
            @error('email')
            <p class="text-[11px] mt-1" style="color: #dc2626;">{{ $message }}</p> @enderror

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && !$user->hasVerifiedEmail())
                <p class="text-[12px] mt-2" style="color: var(--text-secondary);">
                    بريدك الإلكتروني غير موثق.
                    <button form="send-verification" class="font-semibold underline" style="color: var(--gold);">
                        اضغط هنا لإعادة إرسال رابط التوثيق
                    </button>
                </p>
                @if (session('status') === 'verification-link-sent')
                    <p class="text-[12px] mt-1 font-medium" style="color: #166534;">
                        تم إرسال رابط توثيق جديد إلى بريدك الإلكتروني.
                    </p>
                @endif
            @endif
        </div>

        <div class="flex items-center gap-3 pt-2">
            <button type="submit"
                class="h-11 px-6 rounded-lg font-semibold text-[13px] text-white transition-all duration-200 hover:opacity-90"
                style="background-color: var(--gold);">
                حفظ التغييرات
            </button>

            @if (session('status') === 'profile-updated')
                <p class="text-[12px] font-medium" style="color: #166534;">
                    تم الحفظ بنجاح.
                </p>
            @endif
        </div>
    </form>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}" class="hidden">
        @csrf
    </form>

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