@extends('layouts.app')

@section('title', $page->title . ' | متجري')

@section('content')

    <style>
        .page-view {
            --gold: #14b8a6;
            --gold-soft: rgba(20, 184, 166, 0.1);
            --border-light: #e6e6e4;
            --text-primary: #0f0f0f;
            --text-secondary: #6b6b5e;
            --text-tertiary: #9a9a8c;
            --bg-primary: #fafaf9;
        }
        .dark .page-view {
            --gold: #2dd4bf;
            --gold-soft: rgba(45, 212, 191, 0.15);
            --border-light: #262626;
            --text-primary: #f5f5f0;
            --text-secondary: #969690;
            --text-tertiary: #6e6e64;
            --bg-primary: #0a0a0a;
        }

        /* Rich content styling */
        .page-content {
            font-size: 15px;
            line-height: 1.85;
            color: rgb(var(--text-secondary, 107 107 94));
        }
        .page-content h2 {
            font-family: 'IBM Plex Sans Arabic', 'Cairo', sans-serif;
            font-size: 1.5rem;
            font-weight: 700;
            color: inherit;
            margin-top: 3rem;
            margin-bottom: 1.25rem;
            padding-bottom: 0.75rem;
            border-bottom: 1px solid #e6e6e4;
            letter-spacing: -0.02em;
        }
        .dark .page-content h2 { border-bottom-color: #262626; }
        .page-content h2:first-child { margin-top: 0; }

        .page-content h3 {
            font-family: 'IBM Plex Sans Arabic', 'Cairo', sans-serif;
            font-size: 1.15rem;
            font-weight: 700;
            margin-top: 2rem;
            margin-bottom: 0.85rem;
            letter-spacing: -0.01em;
        }

        .page-content p { margin-bottom: 1.1rem; }

        .page-content ul,
        .page-content ol {
            margin: 1.25rem 0;
            padding-right: 1.5rem;
        }
        .page-content ul { list-style: disc; }
        .page-content ol { list-style: decimal; }
        .page-content li { margin-bottom: 0.5rem; padding-right: 0.25rem; }
        .page-content li::marker { color: #14b8a6; }

        .page-content a {
            color: #005a96;
            text-decoration: underline;
            text-underline-offset: 4px;
            text-decoration-thickness: 1px;
            transition: opacity 0.15s;
        }
        .dark .page-content a { color: #2dd4bf; }
        .page-content a:hover { opacity: 0.7; }

        .page-content strong {
            color: inherit;
            font-weight: 700;
        }

        .page-content blockquote {
            border-right: 3px solid #14b8a6;
            padding-right: 1.5rem;
            margin: 1.75rem 0;
            color: rgb(var(--text-secondary, 107 107 94));
            font-style: italic;
            font-size: 1.05em;
        }

        .page-content code {
            background: #f5f5f4;
            padding: 0.15rem 0.5rem;
            border-radius: 4px;
            font-family: monospace;
            font-size: 0.9em;
        }
        .dark .page-content code { background: #141414; }

        .page-content hr {
            border: 0;
            border-top: 1px solid #e6e6e4;
            margin: 2rem 0;
        }
        .dark .page-content hr { border-top-color: #262626; }

        .page-content img {
            border-radius: 4px;
            margin: 1.5rem 0;
            max-width: 100%;
            height: auto;
        }

        .page-content table {
            width: 100%;
            border-collapse: collapse;
            margin: 1.5rem 0;
            font-size: 14px;
        }
        .page-content th,
        .page-content td {
            padding: 12px 16px;
            text-align: right;
            border-bottom: 1px solid #e6e6e4;
        }
        .dark .page-content th,
        .dark .page-content td { border-bottom-color: #262626; }
        .page-content th {
            background: #f5f5f4;
            font-weight: 700;
        }
        .dark .page-content th { background: #141414; }
    </style>

    <div class="page-view container-narrow pt-12 lg:pt-16 pb-24">

        {{-- ════════════ BREADCRUMB ════════════ --}}
        <nav class="flex items-center gap-3 text-[10px] font-medium uppercase tracking-[0.2em] text-ink-faint dark:text-cream/40 mb-12 flex-wrap" aria-label="breadcrumb">
            <a href="{{ route('home') }}" class="hover:text-forest dark:hover:text-gold transition-colors">الرئيسية</a>
            <span class="opacity-40">/</span>
            <span class="text-ink dark:text-cream">{{ $page->title }}</span>
        </nav>

        {{-- ════════════ HEADER ════════════ --}}
        <header class="mb-14 pb-10 border-b border-stone-200 dark:border-stone-800">
            <span class="eyebrow block mb-5">— معلومات</span>

            <h1 class="display-2 mb-6 text-balance">
                {{ $page->title }}
            </h1>

            @if($page->subtitle)
                <p class="text-lg text-ink-muted dark:text-cream/60 text-pretty max-w-2xl mb-6 leading-relaxed">
                    {{ $page->subtitle }}
                </p>
            @endif

            @if($page->updated_at)
                <div class="flex items-center gap-3 text-xs text-ink-faint dark:text-cream/40">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    <span>آخر تحديث:</span>
                    <span dir="ltr">{{ $page->updated_at->format('Y-m-d') }}</span>
                </div>
            @endif
        </header>

        {{-- ════════════ CONTENT ════════════ --}}
        <article class="page-content mb-20">
            {!! $page->content !!}
        </article>

        {{-- ════════════ CTA ════════════ --}}
        <div class="pt-12 border-t border-stone-200 dark:border-stone-800">
            <div class="grid lg:grid-cols-12 gap-10 items-center">

                <div class="lg:col-span-7">
                    <span class="eyebrow block mb-4">— لم تجد ما تبحث عنه؟</span>
                    <h2 class="display-2 mb-4 text-balance">
                        فريقنا
                        <span class="text-forest dark:text-gold">جاهز لمساعدتك</span>
                    </h2>
                    <p class="text-base text-ink-muted dark:text-cream/60 text-pretty max-w-md">
                        تواصل معنا لأي استفسار حول هذه الصفحة أو خدماتنا.
                    </p>
                </div>

                <div class="lg:col-span-5 lg:text-left">
                    <a href="{{ route('pages.contact') }}" class="btn-solid group inline-flex">
                        اتصل بنا
                        <svg class="w-4 h-4 transition-transform group-hover:-translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                        </svg>
                    </a>
                </div>
            </div>
        </div>

    </div>

@endsection