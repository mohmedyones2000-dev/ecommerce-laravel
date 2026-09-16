@extends('layouts.app')

@section('title', 'الأسئلة الشائعة | متجري')

@section('content')

    <div class="max-w-[900px] mx-auto px-4 sm:px-6 lg:px-8 py-8">

        <div class="mb-8 text-center">
            <h1 class="text-2xl md:text-3xl font-bold mb-2" style="color: var(--text-primary);">الأسئلة الشائعة</h1>
            <p class="text-[13px]" style="color: var(--text-secondary);">
                تجد هنا إجابات لأكثر الأسئلة شيوعاً. إذا لم تجد إجابتك، تواصل معنا.
            </p>
        </div>

        @if($faqs->count())

            <div class="space-y-3">
                @foreach($faqs as $index => $faq)
                    <div class="rounded-xl border overflow-hidden transition-all duration-200"
                        style="background-color: var(--bg-primary); border-color: var(--border-light);" x-data="{ open: false }">

                        <button type="button" @click="open = !open"
                            class="w-full px-5 py-4 flex items-center justify-between gap-4 text-right transition-colors duration-150"
                            onmouseover="this.style.backgroundColor='var(--bg-secondary)';"
                            onmouseout="this.style.backgroundColor='transparent';">

                            <span class="flex items-center gap-3 flex-1 min-w-0">
                                <span class="w-7 h-7 rounded-lg flex items-center justify-center text-[12px] font-bold shrink-0"
                                    style="background-color: var(--gold-soft); color: var(--gold);">
                                    {{ $index + 1 }}
                                </span>
                                <span class="text-[14px] font-semibold truncate" style="color: var(--text-primary);">
                                    {{ $faq->question }}
                                </span>
                            </span>

                            <svg class="w-4 h-4 shrink-0 transition-transform duration-200" :class="{ 'rotate-180': open }"
                                style="color: var(--gold);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>

                        <div x-show="open" x-cloak x-transition:enter="transition ease-out duration-200"
                            x-transition:enter-start="opacity-0 -translate-y-1" x-transition:enter-end="opacity-100 translate-y-0"
                            class="px-5 py-4 border-t"
                            style="border-color: var(--border-light); background-color: var(--bg-secondary);">
                            <p class="text-[13px] leading-relaxed pr-10" style="color: var(--text-secondary);">
                                {{ $faq->answer }}
                            </p>
                        </div>
                    </div>
                @endforeach
            </div>

        @else

            <div class="rounded-xl border p-14 text-center"
                style="background-color: var(--bg-primary); border-color: var(--border-light);">
                <div class="w-16 h-16 rounded-xl flex items-center justify-center mx-auto mb-5"
                    style="background-color: var(--bg-tertiary);">
                    <svg class="w-8 h-8" style="color: var(--text-tertiary);" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <h3 class="text-lg font-semibold mb-2" style="color: var(--text-primary);">لا توجد أسئلة حالياً</h3>
                <p class="text-[13px]" style="color: var(--text-secondary);">سيتم إضافة الأسئلة الشائعة قريباً</p>
            </div>

        @endif

        <div class="mt-8 rounded-xl border p-6 text-center"
            style="background-color: var(--bg-primary); border-color: var(--border-light);">
            <h3 class="text-[15px] font-bold mb-2" style="color: var(--text-primary);">لم تجد إجابتك؟</h3>
            <p class="text-[13px] mb-4" style="color: var(--text-secondary);">
                فريقنا جاهز لمساعدتك في أي وقت
            </p>
            <a href="{{ route('pages.contact') }}"
                class="inline-flex items-center gap-2 h-10 px-5 rounded-lg font-semibold text-[13px] text-white transition-all duration-200 hover:opacity-90"
                style="background-color: var(--gold);">
                تواصل معنا
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
            </a>
        </div>

    </div>

@endsection