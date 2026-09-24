@props(['active'])

@php
    $classes = ($active ?? false)
        ? 'relative inline-flex items-center gap-2 px-3.5 py-2 text-sm font-medium text-ink dark:text-cream transition-colors duration-200'
        : 'relative inline-flex items-center gap-2 px-3.5 py-2 text-sm font-medium text-ink-muted dark:text-cream/60 hover:text-ink dark:hover:text-cream transition-colors duration-200';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
    @if($active ?? false)
        <span class="absolute -bottom-1 right-0 left-0 h-px bg-forest dark:bg-gold"></span>
    @endif
</a>