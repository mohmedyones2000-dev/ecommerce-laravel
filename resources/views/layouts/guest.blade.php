<!DOCTYPE html>
<html lang="ar" dir="rtl" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', $siteSettings->site_name ?? 'متجري')</title>

    @if($siteSettings->favicon ?? null)
        <link rel="icon" href="{{ asset('storage/' . $siteSettings->favicon) }}">
    @else
        <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    @endif

    <meta name="theme-color" content="#005a96">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;500;600;700;800&family=IBM+Plex+Sans+Arabic:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <script>
        (function () {
            const saved = localStorage.getItem('darkMode');
            const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
            if (saved === 'true' || (saved === null && prefersDark)) {
                document.documentElement.classList.add('dark');
            }
        })();
    </script>
</head>

<body class="min-h-screen bg-canvas dark:bg-zinc-950 text-ink dark:text-cream">

    <div class="min-h-screen grid lg:grid-cols-12">

        {{-- ═══════════ FORM SIDE ═══════════ --}}
        <div class="lg:col-span-5 flex flex-col min-h-screen">

            {{-- Top bar --}}
            <div class="flex items-center justify-between px-6 sm:px-10 lg:px-14 py-6">
                <a href="{{ route('home') }}" class="flex items-center gap-3 group">
                    @if($siteSettings->logo ?? null)
                        <img src="{{ asset('storage/' . $siteSettings->logo) }}"
                             alt="{{ $siteSettings->site_name ?? '' }}"
                             class="w-10 h-10 object-cover"
                             style="border-radius: 4px;">
                    @else
                        <x-application-logo size="md" />
                    @endif
                    <span class="font-display text-lg font-bold tracking-tight">
                        {{ $siteSettings->site_name ?? 'متجري' }}
                    </span>
                </a>

                <a href="{{ route('home') }}"
                   class="hidden sm:inline-flex items-center gap-2 text-xs font-semibold text-ink-muted dark:text-cream/50 hover:text-forest dark:hover:text-gold transition-colors">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                    </svg>
                    العودة للرئيسية
                </a>
            </div>

            {{-- Form content --}}
            <div class="flex-1 flex items-center px-6 sm:px-10 lg:px-14 py-10">
                <div class="w-full max-w-md mx-auto">
                    {{ $slot }}
                </div>
            </div>

            {{-- Bottom bar --}}
            <div class="px-6 sm:px-10 lg:px-14 py-6 border-t border-stone-200 dark:border-stone-800">
                <p class="text-xs text-center text-ink-faint dark:text-cream/40">
                    © {{ date('Y') }} {{ $siteSettings->site_name ?? 'متجري' }} — جميع الحقوق محفوظة
                </p>
            </div>
        </div>

        {{-- ═══════════ VISUAL SIDE ═══════════ --}}
        <div class="hidden lg:flex lg:col-span-7 relative overflow-hidden bg-forest dark:bg-zinc-900">

            {{-- Dot pattern --}}
            <div class="absolute inset-0 opacity-[0.08] dark:opacity-[0.12]"
                 style="background-image: radial-gradient(circle at 1px 1px, rgb(255,255,255) 1px, transparent 0); background-size: 32px 32px;"
                 aria-hidden="true"></div>

            {{-- Gradient blobs --}}
            <div class="absolute top-0 right-0 w-[500px] h-[500px] rounded-full bg-gold/20 dark:bg-gold/10 blur-3xl" aria-hidden="true"></div>
            <div class="absolute bottom-0 left-0 w-[600px] h-[600px] rounded-full bg-gold/10 dark:bg-gold/5 blur-3xl" aria-hidden="true"></div>

            {{-- Content --}}
            <div class="relative flex flex-col justify-between w-full p-14 xl:p-20 text-cream">

                <div class="flex items-start justify-between">
                    <span class="eyebrow text-cream/60">— {{ $siteSettings->site_name ?? 'متجري' }}</span>
                    <span class="text-[10px] tracking-[0.3em] uppercase text-cream/40">تجربة تسوق عصرية</span>
                </div>

                <div class="max-w-xl">
                    <p class="font-display text-3xl md:text-4xl lg:text-5xl font-bold leading-[1.15] tracking-tight mb-8 text-balance">
                        مرحباً بك في
                        <span class="text-gold">عالم التسوق</span>
                        الذي يليق بك
                    </p>

                    <p class="text-base text-cream/70 leading-relaxed max-w-md text-pretty">
                        آلاف المنتجات المختارة بعناية، بانتظارك. سجّل دخولك واستمتع بتجربة تسوق سلسة وآمنة.
                    </p>

                    <div class="grid grid-cols-3 gap-8 mt-14 pt-10 border-t border-cream/10">
                        <div>
                            <p class="font-display text-3xl font-bold text-gold mb-1">+50</p>
                            <p class="text-[10px] tracking-[0.2em] uppercase text-cream/50">علامة تجارية</p>
                        </div>
                        <div>
                            <p class="font-display text-3xl font-bold text-gold mb-1">+10K</p>
                            <p class="text-[10px] tracking-[0.2em] uppercase text-cream/50">عميل سعيد</p>
                        </div>
                        <div>
                            <p class="font-display text-3xl font-bold text-gold mb-1">24/7</p>
                            <p class="text-[10px] tracking-[0.2em] uppercase text-cream/50">دعم مباشر</p>
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-between text-[10px] tracking-widest uppercase text-cream/40">
                    <span>صُنع بحب</span>
                    <span>v1.0</span>
                </div>
            </div>
        </div>
    </div>
</body>

</html>