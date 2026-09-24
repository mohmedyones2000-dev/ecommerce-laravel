@props(['status'])

@if ($status)
    <div {{ $attributes->merge([
            'class' => 'flex items-start gap-3 px-5 py-4 text-sm border border-green-200 dark:border-green-900/50 bg-green-50 dark:bg-green-950/20 text-green-800 dark:text-green-300',
            'style' => 'border-radius: 4px;',
            'role' => 'status',
            'aria-live' => 'polite',
        ]) }}>
        <svg class="w-5 h-5 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8"
            aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        <span>{{ $status }}</span>
    </div>
@endif