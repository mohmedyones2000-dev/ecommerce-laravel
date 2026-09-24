@extends('layouts.app')

@section('title', 'اتصل بنا | متجري')

@section('content')

<style>
    .contact-page {
        --gold: #14b8a6;
        --gold-soft: rgba(20, 184, 166, 0.1);
        --border-light: #e6e6e4;
        --text-primary: #0f0f0f;
        --text-secondary: #6b6b5e;
        --text-tertiary: #9a9a8c;
        --bg-primary: #fafaf9;
        --bg-tertiary: #f5f5f4;
    }
    .dark .contact-page {
        --gold: #2dd4bf;
        --gold-soft: rgba(45, 212, 191, 0.15);
        --border-light: #262626;
        --text-primary: #f5f5f0;
        --text-secondary: #969690;
        --text-tertiary: #6e6e64;
        --bg-primary: #0a0a0a;
        --bg-tertiary: #141414;
    }
</style>

<div class="contact-page container-x pt-12 lg:pt-16 pb-24">

    {{-- ════════════ HEADER ════════════ --}}
    <header class="mb-12 lg:mb-16 pb-8 border-b border-stone-200 dark:border-stone-800">
        <nav class="flex items-center gap-3 text-[10px] font-medium uppercase tracking-[0.2em] text-ink-faint dark:text-cream/40 mb-8" aria-label="breadcrumb">
            <a href="{{ route('home') }}" class="hover:text-forest dark:hover:text-gold transition-colors">الرئيسية</a>
            <span class="opacity-40">/</span>
            <span class="text-ink dark:text-cream">اتصل بنا</span>
        </nav>

        <div class="max-w-2xl">
            <span class="eyebrow block mb-4">— تواصل معنا</span>
            <h1 class="display-2 mb-4 text-balance">
                نسعد
                <span class="text-forest dark:text-gold">بسماعك</span>
            </h1>
            <p class="text-base text-ink-muted dark:text-cream/60 text-pretty leading-relaxed">
                هل لديك سؤال أو استفسار؟ فريقنا جاهز لمساعدتك في أي وقت.
            </p>
        </div>
    </header>

    @if(session('success'))
        <div class="px-5 py-4 mb-8 text-sm border border-green-200 dark:border-green-900/50 bg-green-50 dark:bg-green-950/20 text-green-800 dark:text-green-300 flex items-center gap-3" style="border-radius: 4px;">
            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="px-5 py-4 mb-8 text-sm border border-red-200 dark:border-red-900/50 bg-red-50 dark:bg-red-950/20 text-red-800 dark:text-red-300" style="border-radius: 4px;">
            <ul class="space-y-1">
                @foreach($errors->all() as $error)
                    <li class="flex items-start gap-2">
                        <span class="w-1 h-1 rounded-full bg-red-500 mt-2 shrink-0"></span>
                        {{ $error }}
                    </li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="grid lg:grid-cols-12 gap-10 lg:gap-16">

        {{-- ════════════ FORM ════════════ --}}
        <div class="lg:col-span-7">
            <div class="border border-stone-200 dark:border-stone-800" style="border-radius: 4px;">

                <div class="px-8 py-6 border-b border-stone-200 dark:border-stone-800">
                    <span class="text-[10px] font-semibold tracking-widest uppercase text-ink-muted dark:text-cream/60">
                        أرسل لنا رسالة
                    </span>
                </div>

                <form action="{{ route('pages.contact.store') }}" method="POST" class="p-8 space-y-6">
                    @csrf

                    {{-- Row 1: Name + Email --}}
                    <div class="grid sm:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-[10px] font-semibold tracking-widest uppercase text-ink-muted dark:text-cream/60 mb-3">
                                الاسم الكامل
                            </label>
                            <input type="text"
                                   name="name"
                                   value="{{ old('name') }}"
                                   required
                                   placeholder="أدخل اسمك"
                                   class="w-full h-12 px-4 text-sm bg-transparent border border-stone-300 dark:border-stone-700 focus:border-forest dark:focus:border-gold focus:outline-none text-ink dark:text-cream placeholder:text-ink-faint dark:placeholder:text-cream/30 transition-colors"
                                   style="border-radius: 4px;">
                        </div>

                        <div>
                            <label class="block text-[10px] font-semibold tracking-widest uppercase text-ink-muted dark:text-cream/60 mb-3">
                                البريد الإلكتروني
                            </label>
                            <input type="email"
                                   name="email"
                                   value="{{ old('email') }}"
                                   required
                                   placeholder="example@email.com"
                                   dir="ltr"
                                   class="w-full h-12 px-4 text-sm bg-transparent border border-stone-300 dark:border-stone-700 focus:border-forest dark:focus:border-gold focus:outline-none text-ink dark:text-cream placeholder:text-ink-faint dark:placeholder:text-cream/30 transition-colors text-left"
                                   style="border-radius: 4px;">
                        </div>
                    </div>

                    {{-- Subject --}}
                    <div>
                        <label class="block text-[10px] font-semibold tracking-widest uppercase text-ink-muted dark:text-cream/60 mb-3">
                            الموضوع
                        </label>
                        <input type="text"
                               name="subject"
                               value="{{ old('subject') }}"
                               required
                               placeholder="موضوع الرسالة"
                               class="w-full h-12 px-4 text-sm bg-transparent border border-stone-300 dark:border-stone-700 focus:border-forest dark:focus:border-gold focus:outline-none text-ink dark:text-cream placeholder:text-ink-faint dark:placeholder:text-cream/30 transition-colors"
                               style="border-radius: 4px;">
                    </div>

                    {{-- Message --}}
                    <div>
                        <label class="block text-[10px] font-semibold tracking-widest uppercase text-ink-muted dark:text-cream/60 mb-3">
                            الرسالة
                        </label>
                        <textarea name="message"
                                  rows="6"
                                  required
                                  placeholder="اكتب رسالتك بالتفصيل..."
                                  class="w-full px-4 py-3 text-sm bg-transparent border border-stone-300 dark:border-stone-700 focus:border-forest dark:focus:border-gold focus:outline-none text-ink dark:text-cream placeholder:text-ink-faint dark:placeholder:text-cream/30 resize-none transition-colors"
                                  style="border-radius: 4px;">{{ old('message') }}</textarea>
                    </div>

                    {{-- Submit --}}
                    <button type="submit"
                            class="group flex items-center justify-center gap-3 w-full h-14 font-display font-bold text-sm tracking-widest uppercase text-white bg-forest dark:bg-gold dark:text-ink hover:bg-gold dark:hover:bg-forest dark:hover:text-cream transition-all"
                            style="border-radius: 4px;">
                        إرسال الرسالة
                        <svg class="w-4 h-4 transition-transform group-hover:-translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                    </button>
                </form>
            </div>
        </div>

        {{-- ════════════ INFO SIDEBAR ════════════ --}}
        <div class="lg:col-span-5 space-y-5">

            {{-- Quick Contact Cards --}}
            @php
                $contacts = array_filter([
                    $siteSettings->email ? [
                        'title' => 'البريد الإلكتروني',
                        'value' => $siteSettings->email,
                        'url' => 'mailto:' . $siteSettings->email,
                        'dir' => 'ltr',
                        'icon' => 'M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z',
                    ] : null,
                    $siteSettings->phone ? [
                        'title' => 'الهاتف',
                        'value' => $siteSettings->phone,
                        'url' => 'tel:' . $siteSettings->phone,
                        'dir' => 'ltr',
                        'icon' => 'M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z',
                    ] : null,
                    $siteSettings->address ? [
                        'title' => 'العنوان',
                        'value' => $siteSettings->address,
                        'url' => null,
                        'dir' => 'rtl',
                        'icon' => 'M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z M15 11a3 3 0 11-6 0 3 3 0 016 0z',
                    ] : null,
                ]);
            @endphp

            @foreach($contacts as $contact)
                <div class="border border-stone-200 dark:border-stone-800 p-6 group hover:border-forest dark:hover:border-gold transition-colors" style="border-radius: 4px;">
                    <div class="flex items-start gap-4">
                        <div class="w-11 h-11 flex items-center justify-center bg-forest/5 dark:bg-gold/10 text-forest dark:text-gold shrink-0" style="border-radius: 4px;">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="{{ $contact['icon'] }}" />
                            </svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-[10px] font-semibold tracking-widest uppercase text-ink-muted dark:text-cream/50 mb-2">
                                {{ $contact['title'] }}
                            </p>
                            @if($contact['url'])
                                <a href="{{ $contact['url'] }}"
                                   dir="{{ $contact['dir'] }}"
                                   class="block text-sm font-medium text-ink dark:text-cream hover:text-forest dark:hover:text-gold transition-colors break-all {{ $contact['dir'] === 'ltr' ? 'text-left' : '' }}">
                                    {{ $contact['value'] }}
                                </a>
                            @else
                                <p class="text-sm font-medium text-ink dark:text-cream">
                                    {{ $contact['value'] }}
                                </p>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach

            {{-- Working Hours --}}
            @if($siteSettings->working_hours_weekday || $siteSettings->working_hours_weekend)
                <div class="border border-stone-200 dark:border-stone-800 p-6" style="border-radius: 4px;">
                    <div class="flex items-start gap-4">
                        <div class="w-11 h-11 flex items-center justify-center bg-forest/5 dark:bg-gold/10 text-forest dark:text-gold shrink-0" style="border-radius: 4px;">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div class="flex-1">
                            <p class="text-[10px] font-semibold tracking-widest uppercase text-ink-muted dark:text-cream/50 mb-3">
                                أوقات العمل
                            </p>
                            @if($siteSettings->working_hours_weekday)
                                <div class="flex items-baseline justify-between gap-4 mb-2">
                                    <span class="text-xs text-ink-muted dark:text-cream/60">أيام الأسبوع</span>
                                    <span class="text-sm font-medium">{{ $siteSettings->working_hours_weekday }}</span>
                                </div>
                            @endif
                            @if($siteSettings->working_hours_weekend)
                                <div class="flex items-baseline justify-between gap-4">
                                    <span class="text-xs text-ink-muted dark:text-cream/60">نهاية الأسبوع</span>
                                    <span class="text-sm font-medium">{{ $siteSettings->working_hours_weekend }}</span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endif

            {{-- Social Media --}}
            @if($siteSettings->facebook || $siteSettings->instagram || $siteSettings->twitter || $siteSettings->whatsapp)
                <div class="border border-stone-200 dark:border-stone-800 p-6" style="border-radius: 4px;">
                    <p class="text-[10px] font-semibold tracking-widest uppercase text-ink-muted dark:text-cream/50 mb-4">
                        تابعنا
                    </p>
                    <div class="grid grid-cols-4 gap-2">
                        @php
                            $socials = array_filter([
                                $siteSettings->facebook ? [
                                    'url' => $siteSettings->facebook,
                                    'label' => 'فيسبوك',
                                    'hover' => '#1877f2',
                                    'path' => 'M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z',
                                ] : null,
                                $siteSettings->instagram ? [
                                    'url' => $siteSettings->instagram,
                                    'label' => 'إنستغرام',
                                    'hover' => '#e4405f',
                                    'path' => 'M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z',
                                ] : null,
                                $siteSettings->twitter ? [
                                    'url' => $siteSettings->twitter,
                                    'label' => 'تويتر',
                                    'hover' => '#0f172a',
                                    'path' => 'M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z',
                                ] : null,
                                $siteSettings->whatsapp ? [
                                    'url' => 'https://wa.me/' . $siteSettings->whatsapp,
                                    'label' => 'واتساب',
                                    'hover' => '#25d366',
                                    'path' => 'M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z',
                                ] : null,
                            ]);
                        @endphp

                        @foreach($socials as $social)
                            <a href="{{ $social['url'] }}"
                               target="_blank"
                               rel="noopener noreferrer"
                               title="{{ $social['label'] }}"
                               class="aspect-square flex items-center justify-center border border-stone-300 dark:border-stone-700 text-ink-muted dark:text-cream/60 transition-all duration-200 hover:text-white hover:border-transparent"
                               style="border-radius: 4px;"
                               onmouseover="this.style.backgroundColor='{{ $social['hover'] }}'"
                               onmouseout="this.style.backgroundColor=''">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="{{ $social['path'] }}" />
                                </svg>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif

        </div>
    </div>

</div>

@endsection