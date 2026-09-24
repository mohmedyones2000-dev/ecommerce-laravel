<button {{ $attributes->merge([
    'type' => 'submit',
    'class' => 'inline-flex items-center justify-center gap-3 h-12 px-6 font-display font-bold text-xs tracking-widest uppercase text-white bg-red-600 hover:bg-red-700 active:scale-[0.98] focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 focus:ring-offset-canvas dark:focus:ring-offset-zinc-950 disabled:opacity-50 disabled:cursor-not-allowed transition-all',
    'style' => 'border-radius: 4px;',
]) }}>
    {{ $slot }}
</button>