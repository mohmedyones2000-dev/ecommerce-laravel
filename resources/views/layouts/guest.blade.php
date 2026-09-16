<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'متجري')</title>

    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <meta name="theme-color" content="#C9A961">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        :root {
            --gold: #C9A961;
            --gold-dark: #A88844;
            --gold-soft: #F8F4EA;
            --bg-primary: #FFFFFF;
            --bg-secondary: #F9FAFB;
            --bg-tertiary: #F3F4F6;
            --text-primary: #111827;
            --text-secondary: #6B7280;
            --text-tertiary: #9CA3AF;
            --border-light: #E5E7EB;
            --border-medium: #D1D5DB;
        }

        html.dark {
            --bg-primary: #18181B;
            --bg-secondary: #09090B;
            --bg-tertiary: #27272A;
            --text-primary: #FAFAFA;
            --text-secondary: #A1A1AA;
            --text-tertiary: #71717A;
            --border-light: #27272A;
            --border-medium: #3F3F46;
            --gold-soft: #1F1A0F;
        }

        * {
            box-sizing: border-box;
        }

        html {
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        body {
            font-family: 'Cairo', system-ui, -apple-system, sans-serif;
            background-color: var(--bg-secondary);
            color: var(--text-primary);
            line-height: 1.6;
            letter-spacing: -0.01em;
        }

        h1,
        h2,
        h3,
        h4,
        h5,
        h6 {
            font-weight: 700;
            letter-spacing: -0.02em;
            color: var(--text-primary);
        }

        html.dark input,
        html.dark select,
        html.dark textarea {
            background-color: var(--bg-tertiary) !important;
            border-color: var(--border-light) !important;
            color: var(--text-primary) !important;
        }

        html.dark input::placeholder,
        html.dark textarea::placeholder {
            color: var(--text-tertiary) !important;
        }

        html.dark input:focus,
        html.dark select:focus,
        html.dark textarea:focus {
            border-color: var(--gold) !important;
            background-color: var(--bg-primary) !important;
        }
    </style>

    <script>
        (function () {
            const saved = localStorage.getItem('darkMode');
            if (saved === 'true') {
                document.documentElement.classList.add('dark');
            }
        })();
    </script>
</head>

<body class="antialiased">

    <div class="min-h-screen flex flex-col items-center justify-center py-12 px-4">

        <a href="{{ route('home') }}" class="flex items-center gap-2.5 mb-8">
            <div class="w-10 h-10 rounded-lg flex items-center justify-center text-white text-sm font-bold"
                style="background-color: var(--gold);">
                م
            </div>
            <span class="text-lg font-bold tracking-tight" style="color: var(--text-primary);">متجري</span>
        </a>

        <div class="w-full max-w-md rounded-xl border p-6 sm:p-8"
            style="background-color: var(--bg-primary); border-color: var(--border-light);">

            {{ $slot }}

        </div>

        <div class="text-center mt-6">
            <a href="{{ route('home') }}" class="text-[12px] font-medium transition-colors duration-150"
                style="color: var(--text-tertiary);" onmouseover="this.style.color='var(--gold)';"
                onmouseout="this.style.color='var(--text-tertiary)';">
                العودة إلى المتجر
            </a>
        </div>

    </div>

</body>

</html>