@extends('layouts.app')

@section('title', $page->title . ' | متجري')

@section('content')

    <div class="max-w-[900px] mx-auto px-4 sm:px-6 lg:px-8 py-8">

        <nav class="flex items-center gap-2 text-[12px] mb-6 flex-wrap" style="color: var(--text-tertiary);">
            <a href="{{ route('home') }}" class="transition-colors duration-150 hover:text-[color:var(--gold)]">الرئيسية</a>
            <span>/</span>
            <span style="color: var(--text-primary);">{{ $page->title }}</span>
        </nav>

        <div class="rounded-xl border overflow-hidden mb-6"
            style="background-color: var(--bg-primary); border-color: var(--border-light);">

            <div class="px-6 md:px-10 py-8 border-b" style="border-color: var(--border-light);">
                <h1 class="text-2xl md:text-3xl font-bold mb-2" style="color: var(--text-primary);">
                    {{ $page->title }}
                </h1>

                @if($page->subtitle)
                    <p class="text-[14px] mb-2" style="color: var(--text-secondary);">
                        {{ $page->subtitle }}
                    </p>
                @endif

                @if($page->updated_at)
                    <p class="text-[11px]" style="color: var(--text-tertiary);">
                        آخر تحديث: {{ $page->updated_at->format('Y-m-d') }}
                    </p>
                @endif
            </div>

            <div class="px-6 md:px-10 py-8">
                <div class="page-content text-[14px] leading-loose" style="color: var(--text-secondary);">
                    {!! $page->content !!}
                </div>
            </div>

        </div>

        <div class="rounded-xl border p-6 text-center"
            style="background-color: var(--bg-primary); border-color: var(--border-light);">
            <h2 class="text-[15px] font-bold mb-2" style="color: var(--text-primary);">هل لديك استفسار؟</h2>
            <p class="text-[13px] mb-4" style="color: var(--text-secondary);">
                فريقنا جاهز لمساعدتك في أي وقت
            </p>
            <a href="{{ route('pages.contact') }}"
                class="inline-flex items-center gap-2 h-10 px-5 rounded-lg font-semibold text-[13px] text-white transition-all duration-200 hover:opacity-90"
                style="background-color: var(--gold);">
                اتصل بنا
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
            </a>
        </div>

    </div>

    <style>
        .page-content h2 {
            font-size: 1.25rem;
            font-weight: 700;
            color: var(--text-primary);
            margin-top: 2rem;
            margin-bottom: 1rem;
            padding-bottom: 0.5rem;
            border-bottom: 1px solid var(--border-light);
        }

        .page-content h2:first-child {
            margin-top: 0;
        }

        .page-content h3 {
            font-size: 1.1rem;
            font-weight: 600;
            color: var(--text-primary);
            margin-top: 1.5rem;
            margin-bottom: 0.75rem;
        }

        .page-content p {
            margin-bottom: 1rem;
        }

        .page-content ul,
        .page-content ol {
            margin: 1rem 0;
            padding-right: 1.5rem;
        }

        .page-content ul {
            list-style: disc;
        }

        .page-content ol {
            list-style: decimal;
        }

        .page-content li {
            margin-bottom: 0.5rem;
        }

        .page-content a {
            color: var(--gold);
            text-decoration: underline;
        }

        .page-content strong {
            color: var(--text-primary);
            font-weight: 600;
        }

        .page-content blockquote {
            border-right: 3px solid var(--gold);
            padding-right: 1rem;
            margin: 1rem 0;
            color: var(--text-secondary);
            font-style: italic;
        }
    </style>

@endsection