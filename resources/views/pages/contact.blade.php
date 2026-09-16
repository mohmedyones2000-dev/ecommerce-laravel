@extends('layouts.app')

@section('title', 'اتصل بنا | متجري')

@section('content')

    <div class="max-w-[1200px] mx-auto px-4 sm:px-6 lg:px-8 py-8">

        <div class="mb-8 text-center">
            <h1 class="text-2xl md:text-3xl font-bold mb-2" style="color: var(--text-primary);">اتصل بنا</h1>
            <p class="text-[13px]" style="color: var(--text-secondary);">
                هل لديك سؤال أو استفسار؟ يسعدنا تواصلك معنا
            </p>
        </div>

        @if(session('success'))
            <div class="px-4 py-3 rounded-lg mb-5 text-[13px] max-w-3xl mx-auto" style="background-color: #f0fdf4; color: #166534; border: 1px solid #bbf7d0;">
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="px-4 py-3 rounded-lg mb-5 text-[13px] max-w-3xl mx-auto" style="background-color: #fef2f2; color: #991b1b; border: 1px solid #fecaca;">
                @foreach($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <div class="grid md:grid-cols-3 gap-6">

            <div class="md:col-span-2">
                <div class="rounded-xl border overflow-hidden" style="background-color: var(--bg-primary); border-color: var(--border-light);">

                    <div class="px-5 py-4 border-b" style="border-color: var(--border-light);">
                        <h2 class="text-[14px] font-semibold" style="color: var(--text-primary);">أرسل لنا رسالة</h2>
                    </div>

                    <form action="{{ route('pages.contact.store') }}" method="POST" class="p-5 space-y-4">
                        @csrf

                        <div class="grid sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-[12px] font-semibold mb-2" style="color: var(--text-primary);">الاسم الكامل</label>
                                <input type="text" name="name" value="{{ old('name') }}" required
                                       placeholder="أدخل اسمك"
                                       class="w-full h-11 rounded-lg px-3 text-[13px] focus:outline-none focus:ring-2 transition-all duration-150"
                                       style="background-color: var(--bg-primary); border: 1px solid var(--border-light); color: var(--text-primary); --tw-ring-color: var(--gold);">
                            </div>

                            <div>
                                <label class="block text-[12px] font-semibold mb-2" style="color: var(--text-primary);">البريد الإلكتروني</label>
                                <input type="email" name="email" value="{{ old('email') }}" required
                                       placeholder="example@email.com"
                                       class="w-full h-11 rounded-lg px-3 text-[13px] focus:outline-none focus:ring-2 transition-all duration-150"
                                       style="background-color: var(--bg-primary); border: 1px solid var(--border-light); color: var(--text-primary); --tw-ring-color: var(--gold);">
                            </div>
                        </div>

                        <div>
                            <label class="block text-[12px] font-semibold mb-2" style="color: var(--text-primary);">الموضوع</label>
                            <input type="text" name="subject" value="{{ old('subject') }}" required
                                   placeholder="موضوع الرسالة"
                                   class="w-full h-11 rounded-lg px-3 text-[13px] focus:outline-none focus:ring-2 transition-all duration-150"
                                   style="background-color: var(--bg-primary); border: 1px solid var(--border-light); color: var(--text-primary); --tw-ring-color: var(--gold);">
                        </div>

                        <div>
                            <label class="block text-[12px] font-semibold mb-2" style="color: var(--text-primary);">الرسالة</label>
                            <textarea name="message" rows="6" required
                                      placeholder="اكتب رسالتك بالتفصيل..."
                                      class="w-full rounded-lg py-3 px-3 text-[13px] focus:outline-none focus:ring-2 transition-all duration-150"
                                      style="background-color: var(--bg-primary); border: 1px solid var(--border-light); color: var(--text-primary); --tw-ring-color: var(--gold);">{{ old('message') }}</textarea>
                        </div>

                        <button type="submit"
                                class="w-full h-11 rounded-lg font-semibold text-[13px] text-white transition-all duration-200 hover:opacity-90"
                                style="background-color: var(--gold);">
                            إرسال الرسالة
                        </button>
                    </form>

                </div>
            </div>

            <div class="md:col-span-1 space-y-4">

                @if($siteSettings->email)
                    <div class="rounded-xl border p-5" style="background-color: var(--bg-primary); border-color: var(--border-light);">
                        <div class="w-10 h-10 rounded-lg flex items-center justify-center mb-3" style="background-color: var(--gold-soft);">
                            <svg class="w-5 h-5" style="color: var(--gold);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <h3 class="text-[12px] font-semibold mb-1" style="color: var(--text-primary);">البريد الإلكتروني</h3>
                        <a href="mailto:{{ $siteSettings->email }}" class="text-[12px] break-all transition-colors duration-150 hover:text-[color:var(--gold)]"
                           style="color: var(--text-secondary);">
                            {{ $siteSettings->email }}
                        </a>
                    </div>
                @endif

                @if($siteSettings->phone)
                    <div class="rounded-xl border p-5" style="background-color: var(--bg-primary); border-color: var(--border-light);">
                        <div class="w-10 h-10 rounded-lg flex items-center justify-center mb-3" style="background-color: var(--gold-soft);">
                            <svg class="w-5 h-5" style="color: var(--gold);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                            </svg>
                        </div>
                        <h3 class="text-[12px] font-semibold mb-1" style="color: var(--text-primary);">الهاتف</h3>
                        <a href="tel:{{ $siteSettings->phone }}" dir="ltr" class="text-[12px] transition-colors duration-150 hover:text-[color:var(--gold)]"
                           style="color: var(--text-secondary);">
                            {{ $siteSettings->phone }}
                        </a>
                    </div>
                @endif

                @if($siteSettings->address)
                    <div class="rounded-xl border p-5" style="background-color: var(--bg-primary); border-color: var(--border-light);">
                        <div class="w-10 h-10 rounded-lg flex items-center justify-center mb-3" style="background-color: var(--gold-soft);">
                            <svg class="w-5 h-5" style="color: var(--gold);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </div>
                        <h3 class="text-[12px] font-semibold mb-1" style="color: var(--text-primary);">العنوان</h3>
                        <p class="text-[12px]" style="color: var(--text-secondary);">{{ $siteSettings->address }}</p>
                    </div>
                @endif

                @if($siteSettings->working_hours_weekday || $siteSettings->working_hours_weekend)
                    <div class="rounded-xl border p-5" style="background-color: var(--bg-primary); border-color: var(--border-light);">
                        <div class="w-10 h-10 rounded-lg flex items-center justify-center mb-3" style="background-color: var(--gold-soft);">
                            <svg class="w-5 h-5" style="color: var(--gold);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <h3 class="text-[12px] font-semibold mb-2" style="color: var(--text-primary);">أوقات العمل</h3>
                        @if($siteSettings->working_hours_weekday)
                            <p class="text-[12px] mb-0.5" style="color: var(--text-secondary);">{{ $siteSettings->working_hours_weekday }}</p>
                        @endif
                        @if($siteSettings->working_hours_weekend)
                            <p class="text-[12px]" style="color: var(--text-secondary);">{{ $siteSettings->working_hours_weekend }}</p>
                        @endif
                    </div>
                @endif

                @if($siteSettings->facebook || $siteSettings->instagram || $siteSettings->twitter || $siteSettings->whatsapp)
                    <div class="rounded-xl border p-5" style="background-color: var(--bg-primary); border-color: var(--border-light);">
                        <h3 class="text-[12px] font-semibold mb-3" style="color: var(--text-primary);">تابعنا</h3>
                        <div class="flex gap-2 flex-wrap">

                            @if($siteSettings->facebook)
                                <a href="{{ $siteSettings->facebook }}" target="_blank" rel="noopener noreferrer"
                                   title="فيسبوك"
                                   class="w-9 h-9 rounded-lg flex items-center justify-center transition-all duration-150"
                                   style="background-color: var(--bg-tertiary); color: var(--text-secondary);"
                                   onmouseover="this.style.backgroundColor='#1877f2'; this.style.color='white';"
                                   onmouseout="this.style.backgroundColor='var(--bg-tertiary)'; this.style.color='var(--text-secondary)';">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z" />
                                    </svg>
                                </a>
                            @endif

                            @if($siteSettings->instagram)
                                <a href="{{ $siteSettings->instagram }}" target="_blank" rel="noopener noreferrer"
                                   title="إنستغرام"
                                   class="w-9 h-9 rounded-lg flex items-center justify-center transition-all duration-150"
                                   style="background-color: var(--bg-tertiary); color: var(--text-secondary);"
                                   onmouseover="this.style.backgroundColor='#e4405f'; this.style.color='white';"
                                   onmouseout="this.style.backgroundColor='var(--bg-tertiary)'; this.style.color='var(--text-secondary)';">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z" />
                                    </svg>
                                </a>
                            @endif

                            @if($siteSettings->twitter)
                                <a href="{{ $siteSettings->twitter }}" target="_blank" rel="noopener noreferrer"
                                   title="تويتر"
                                   class="w-9 h-9 rounded-lg flex items-center justify-center transition-all duration-150"
                                   style="background-color: var(--bg-tertiary); color: var(--text-secondary);"
                                   onmouseover="this.style.backgroundColor='#0f172a'; this.style.color='white';"
                                   onmouseout="this.style.backgroundColor='var(--bg-tertiary)'; this.style.color='var(--text-secondary)';">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z" />
                                    </svg>
                                </a>
                            @endif

                            @if($siteSettings->whatsapp)
                                <a href="https://wa.me/{{ $siteSettings->whatsapp }}" target="_blank" rel="noopener noreferrer"
                                   title="واتساب"
                                   class="w-9 h-9 rounded-lg flex items-center justify-center transition-all duration-150"
                                   style="background-color: var(--bg-tertiary); color: var(--text-secondary);"
                                   onmouseover="this.style.backgroundColor='#25d366'; this.style.color='white';"
                                   onmouseout="this.style.backgroundColor='var(--bg-tertiary)'; this.style.color='var(--text-secondary)';">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z" />
                                    </svg>
                                </a>
                            @endif

                        </div>
                    </div>
                @endif

            </div>
        </div>

    </div>

@endsection