@extends('layouts.app')

@section('title', 'الأسئلة الشائعة | متجري')

@section('content')

    <style>
        .faq-page {
            --gold: #14b8a6;
            --gold-soft: rgba(20, 184, 166, 0.1);
            --border-light: #e6e6e4;
            --text-primary: #0f0f0f;
            --text-secondary: #6b6b5e;
            --text-tertiary: #9a9a8c;
            --bg-primary: #fafaf9;
            --bg-secondary: #f5f5f4;
            --bg-tertiary: #f5f5f4;
        }

        .dark .faq-page {
            --gold: #2dd4bf;
            --gold-soft: rgba(45, 212, 191, 0.15);
            --border-light: #262626;
            --text-primary: #f5f5f0;
            --text-secondary: #969690;
            --text-tertiary: #6e6e64;
            --bg-primary: #0a0a0a;
            --bg-secondary: #141414;
            --bg-tertiary: #141414;
        }
    </style>

    <div class="faq-page container-narrow pt-12 lg:pt-16 pb-24">

        {{-- ════════════ HEADER ════════════ --}}
        <header class="mb-12 lg:mb-16 pb-8 border-b border-stone-200 dark:border-stone-800">
            <nav class="flex items-center gap-3 text-[10px] font-medium uppercase tracking-[0.2em] text-ink-faint dark:text-cream/40 mb-8"
                aria-label="breadcrumb">
                <a href="{{ route('home') }}" class="hover:text-forest dark:hover:text-gold transition-colors">الرئيسية</a>
                <span class="opacity-40">/</span>
                <span class="text-ink dark:text-cream">الأسئلة الشائعة</span>
            </nav>

            <div class="max-w-2xl">
                <span class="eyebrow block mb-4">— مركز المساعدة</span>
                <h1 class="display-2 mb-4 text-balance">
                    كيف يمكننا
                    <span class="text-forest dark:text-gold">مساعدتك؟</span>
                </h1>
                <p class="text-base text-ink-muted dark:text-cream/60 text-pretty leading-relaxed">
                    تجد هنا إجابات لأكثر الأسئلة شيوعاً. إن لم تجد ما تبحث عنه، تواصل معنا.
                </p>
            </div>
        </header>

        @if($faqs->count())

            {{-- ════════════ FAQ LIST ════════════ --}}
            <div class="border border-stone-200 dark:border-stone-800 divide-y divide-stone-200 dark:divide-stone-800"
                style="border-radius: 4px;">

                @foreach($faqs as $index => $faq)
                    <div x-data="{ open: {{ $index === 0 ? 'true' : 'false' }} }">

                        {{-- Question --}}
                        <button type="button" @click="open = !open"
                            class="w-full px-6 py-6 flex items-center justify-between gap-6 text-right hover:bg-stone-50 dark:hover:bg-zinc-900 transition-colors">

                            <span class="flex items-center gap-5 flex-1 min-w-0">

                                {{-- Number --}}
                                <span class="font-display text-xs font-bold tracking-[0.2em] text-forest dark:text-gold shrink-0">
                                    {{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}
                                </span>

                                {{-- Question text --}}
                                <span class="font-display text-base font-bold text-ink dark:text-cream leading-snug">
                                    {{ $faq->question }}
                                </span>
                            </span>

                            {{-- Toggle icon --}}
                            <span
                                class="shrink-0 w-8 h-8 flex items-center justify-center text-ink-muted dark:text-cream/50 transition-transform duration-300"
                                :class="{ 'rotate-45': open }">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                                </svg>
                            </span>
                        </button>

                        {{-- Answer --}}
                        <div x-show="open" x-cloak x-transition:enter="transition ease-out duration-300"
                            x-transition:enter-start="opacity-0 -translate-y-1" x-transition:enter-end="opacity-100 translate-y-0">
                            <div class="px-6 pb-8 pr-20">
                                <p class="text-sm leading-relaxed text-ink-muted dark:text-cream/70 text-pretty">
                                    {{ $faq->answer }}
                                </p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

        @else

            {{-- ════════════ EMPTY STATE ════════════ --}}
            <div class="text-center py-20">

                <div class="w-24 h-24 mx-auto mb-8 flex items-center justify-center border border-stone-300 dark:border-stone-700"
                    style="border-radius: 4px;">
                    <svg class="w-10 h-10 text-ink-faint dark:text-cream/30" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24" stroke-width="1">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>

                <span class="eyebrow block mb-4">— فارغ حالياً</span>

                <h2 class="display-2 mb-4 text-balance">
                    لا توجد أسئلة
                    <span class="text-forest dark:text-gold">بعد</span>
                </h2>

                <p class="text-base text-ink-muted dark:text-cream/60 max-w-md mx-auto text-pretty">
                    سيتم إضافة الأسئلة الشائعة قريباً. تابعنا للحصول على التحديثات.
                </p>
            </div>
        @endif

        {{-- ════════════ CTA — لم تجد إجابتك ════════════ --}}
        <div class="mt-16 pt-12 border-t border-stone-200 dark:border-stone-800">
            <div class="grid lg:grid-cols-12 gap-10 items-center">

                <div class="lg:col-span-7">
                    <span class="eyebrow block mb-4">— لم تجد ما تبحث عنه؟</span>
                    <h2 class="display-2 mb-4 text-balance">
                        فريقنا
                        <span class="text-forest dark:text-gold">جاهز لمساعدتك</span>
                    </h2>
                    <p class="text-base text-ink-muted dark:text-cream/60 text-pretty max-w-md">
                        تواصل معنا مباشرة، وسنجيبك في أقرب وقت ممكن.
                    </p>
                </div>

                <div class="lg:col-span-5 lg:text-left">
                    <a href="{{ route('pages.contact') }}" class="btn-solid group inline-flex">
                        تواصل معنا
                        <svg class="w-4 h-4 transition-transform group-hover:-translate-x-1" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                        </svg>
                    </a>
                </div>
            </div>
        </div>

    </div>

@endsection