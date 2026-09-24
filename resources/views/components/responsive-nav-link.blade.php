@props(['active'])

@php
    $classes = ($active ?? false)
        ? 'block w-full px-4 py-3 text-start text-sm font-semibold text-forest dark:text-gold bg-forest/5 dark:bg-gold/10 border-r-2 border-forest dark:border-gold focus:outline-none transition-colors duration-150'
        : 'block w-full px-4 py-3 text-start text-sm font-medium text-ink-soft dark:text-cream/80 hover:bg-stone-50 dark:hover:bg-zinc-900 hover:text-ink dark:hover:text-cream border-r-2 border-transparent focus:outline-none transition-colors duration-150';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>