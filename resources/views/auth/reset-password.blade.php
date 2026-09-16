<x-guest-layout>

    <div class="mb-6">
        <h1 class="text-xl font-bold mb-1.5" style="color: var(--text-primary);">إعادة تعيين كلمة المرور</h1>
        <p class="text-[13px]" style="color: var(--text-secondary);">أدخل كلمة المرور الجديدة لحسابك</p>
    </div>

    @if ($errors->any())
        <div class="px-4 py-3 rounded-lg mb-5 text-[13px]"
            style="background-color: #fef2f2; color: #991b1b; border: 1px solid #fecaca;">
            @foreach ($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif

    <form method="POST" action="{{ route('password.store') }}" class="space-y-5">
        @csrf

        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <div>
            <label for="email" class="block text-[12px] font-semibold mb-2" style="color: var(--text-primary);">
                البريد الإلكتروني
            </label>
            <input id="email" type="email" name="email" value="{{ old('email', $request->email) }}" required autofocus
                autocomplete="username" placeholder="example@email.com"
                class="w-full h-11 rounded-lg px-3 text-[13px] focus:outline-none focus:ring-2 transition-all duration-150"
                style="background-color: var(--bg-primary); border: 1px solid var(--border-light); color: var(--text-primary); --tw-ring-color: var(--gold);">
        </div>

        <div>
            <label for="password" class="block text-[12px] font-semibold mb-2" style="color: var(--text-primary);">
                كلمة المرور الجديدة
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

        <button type="submit"
            class="w-full h-11 rounded-lg font-semibold text-[13px] text-white transition-all duration-200 hover:opacity-90"
            style="background-color: var(--gold);">
            إعادة تعيين كلمة المرور
        </button>

    </form>

</x-guest-layout>