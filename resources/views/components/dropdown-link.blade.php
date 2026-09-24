<a {{ $attributes->merge([
    'class' => 'block w-full px-4 py-2.5 text-sm font-medium text-ink-soft dark:text-cream/80 hover:bg-stone-50 dark:hover:bg-zinc-900 hover:text-forest dark:hover:text-gold focus:outline-none focus:bg-stone-50 dark:focus:bg-zinc-900 focus:text-forest dark:focus:text-gold transition-colors duration-150',
    'role' => 'menuitem',
]) }}>
    {{ $slot }}
</a>