@props(['value'])

<label {{ $attributes->merge([
    'class' => 'block text-[10px] font-semibold tracking-widest uppercase text-ink-muted dark:text-cream/60',
]) }}>
    {{ $value ?? $slot }}
</label>