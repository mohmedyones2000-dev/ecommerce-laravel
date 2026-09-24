<button {{ $attributes->merge([
    'type' => 'button',
    'class' => 'inline-flex items-center justify-center gap-3 h-12 px-6 font-display font-bold text-xs tracking-widest uppercase border border-stone-300 dark:border-stone-700 text-ink dark:text-cream hover:border-forest dark:hover:border-gold hover:text-forest dark:hover:text-gold hover:bg-forest/5 dark:hover:bg-gold/10 active:scale-[0.98] focus:outline-none focus:ring-2 focus:ring-forest dark:focus:ring-gold focus:ring-offset-2 focus:ring-offset-canvas dark:focus:ring-offset-zinc-950 disabled:opacity-50 disabled:cursor-not-allowed transition-all',
    'style' => 'border-radius: 4px;',
]) }}>
    {{ $slot }}
</button>