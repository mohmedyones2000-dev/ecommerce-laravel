<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'متجري')</title>

    @if($siteSettings->favicon)
        <link rel="icon" href="{{ asset('storage/' . $siteSettings->favicon) }}">
        <link rel="apple-touch-icon" href="{{ asset('storage/' . $siteSettings->favicon) }}">
    @else
        <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
        <link rel="apple-touch-icon" href="{{ asset('favicon.png') }}">
    @endif

    <meta name="theme-color" content="{{ $siteSettings->primary_color }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        :root {
            --gold:
                {{ $siteSettings->primary_color }}
            ;
            --gold-dark:
                {{ $siteSettings->primary_color_dark }}
            ;
            --gold-soft:
                {{ $siteSettings->primary_color_soft }}
            ;
            --gold-glow:
                {{ $siteSettings->primary_color_glow }}
            ;

            --bg-primary: #FFFFFF;
            --bg-secondary: #F9FAFB;
            --bg-tertiary: #F3F4F6;

            --text-primary: #111827;
            --text-secondary: #6B7280;
            --text-tertiary: #9CA3AF;

            --border-light: #E5E7EB;
            --border-medium: #D1D5DB;

            --radius-sm: 6px;
            --radius-md: 8px;
            --radius-lg: 12px;
            --radius-xl: 16px;
        }

        * {
            box-sizing: border-box;
        }

        html {
            scroll-behavior: smooth;
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
            line-height: 1.3;
            color: var(--text-primary);
        }

        a {
            transition: color 0.15s ease, background-color 0.15s ease, border-color 0.15s ease, opacity 0.15s ease;
        }

        @keyframes ping-once {
            0% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.4);
            }

            100% {
                transform: scale(1);
            }
        }

        .animate-ping-once {
            animation: ping-once 0.4s ease-out;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-on-scroll {
            opacity: 0;
            transform: translateY(20px);
            transition: opacity 0.5s ease-out, transform 0.5s ease-out;
        }

        .animate-on-scroll.visible {
            opacity: 1;
            transform: translateY(0);
        }

        @keyframes fadeInNotif {
            from {
                opacity: 0;
                transform: translateX(-8px);
            }

            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        .fade-in-notif {
            animation: fadeInNotif 0.25s ease-out;
        }

        .zoom-container {
            overflow: hidden;
            cursor: zoom-in;
            position: relative;
        }

        .zoom-container img {
            transition: transform 0.5s cubic-bezier(0.4, 0, 0.2, 1);
            display: block;
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .zoom-container:hover img {
            transform: scale(1.4);
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

            --gold-soft:
                {{ $siteSettings->primary_color_soft_dark }}
            ;
            --gold-glow:
                {{ $siteSettings->primary_color_glow }}
            ;
        }

        html.dark body {
            background-color: var(--bg-secondary);
            color: var(--text-primary);
        }

        html.dark .bg-white {
            background-color: var(--bg-primary) !important;
            border-color: var(--border-light) !important;
        }

        html.dark .bg-gray-50,
        html.dark .bg-gray-100 {
            background-color: var(--bg-tertiary) !important;
        }

        html.dark .bg-gray-200 {
            background-color: var(--border-light) !important;
        }

        html.dark .text-gray-900,
        html.dark .text-gray-800 {
            color: var(--text-primary) !important;
        }

        html.dark .text-gray-700,
        html.dark .text-gray-600 {
            color: var(--text-secondary) !important;
        }

        html.dark .text-gray-500 {
            color: var(--text-tertiary) !important;
        }

        html.dark .border-gray-100,
        html.dark .border-gray-200,
        html.dark .border-gray-300 {
            border-color: var(--border-light) !important;
        }

        html.dark header {
            background-color: rgba(24, 24, 27, 0.95) !important;
            border-color: var(--border-light) !important;
        }

        html.dark footer {
            background-color: #09090B !important;
            border-color: var(--border-light) !important;
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

        html.dark .hover\:bg-gray-50:hover,
        html.dark .hover\:bg-gray-100:hover {
            background-color: var(--bg-tertiary) !important;
        }

        html.dark .hover\:bg-amber-50:hover {
            background-color: var(--gold-soft) !important;
        }

        html.dark .hero-slide {
            filter: brightness(0.35) saturate(0.7) !important;
        }

        html.dark .hero-slide::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(to left, rgba(0, 0, 0, 0.5) 0%, rgba(0, 0, 0, 0.75) 100%);
            z-index: 1;
            pointer-events: none;
        }

        html.dark .hero-slide>div {
            position: relative;
            z-index: 5;
        }

        html.dark .hero-slide h1,
        html.dark .hero-slide .text-gray-900 {
            color: #FFFFFF !important;
        }

        html.dark .hero-slide p,
        html.dark .hero-slide .text-gray-700 {
            color: #D4D4D8 !important;
        }

        html.dark table thead {
            background-color: var(--bg-tertiary) !important;
        }

        html.dark table tbody tr:nth-child(even) {
            background-color: var(--bg-secondary) !important;
        }

        html.dark table td,
        html.dark table th {
            border-color: var(--border-light) !important;
        }

        html.dark ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }

        html.dark ::-webkit-scrollbar-track {
            background: var(--bg-tertiary);
        }

        html.dark ::-webkit-scrollbar-thumb {
            background: var(--border-medium);
            border-radius: 4px;
        }

        html.dark ::-webkit-scrollbar-thumb:hover {
            background: var(--gold);
        }

        html.dark img {
            opacity: 0.95;
        }

        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }

        ::-webkit-scrollbar-track {
            background: var(--bg-tertiary);
        }

        ::-webkit-scrollbar-thumb {
            background: var(--border-medium);
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: var(--gold);
        }

        .toast-notification {
            font-weight: 500;
            font-size: 13px;
            letter-spacing: -0.01em;
        }

        .product-image-stack {
            isolation: isolate;
        }

        .product-stack-img {
            position: absolute;
            inset: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.4s cubic-bezier(0.4, 0, 0.2, 1),
                opacity 0.4s ease,
                filter 0.4s ease;
            transform-origin: center;
        }

        .product-image-stack:hover .product-stack-img {
            transform: scale(1.05);
        }

        .image-dot.active {
            background-color: white !important;
            width: 16px !important;
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

<body class="antialiased min-h-screen flex flex-col">

    @include('layouts.partials.navbar')

    <main class="flex-1">
        @yield('content')
    </main>

    @include('layouts.partials.footer')

    <script>
        const CSRF_TOKEN = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        async function toggleWishlist(button) {
            const productId = button.dataset.productId;
            const icon = button.querySelector('.wishlist-icon');

            button.style.transform = 'scale(0.9)';
            setTimeout(() => button.style.transform = 'scale(1)', 150);

            try {
                const response = await fetch('{{ route('wishlist.toggle') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': CSRF_TOKEN,
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({ product_id: productId }),
                });

                if (response.status === 401) {
                    window.location.href = '{{ route('login') }}';
                    return;
                }

                const data = await response.json();

                if (data.in_wishlist) {
                    button.classList.remove('text-gray-400');
                    button.classList.add('text-red-500');
                    icon.setAttribute('fill', 'currentColor');
                    icon.classList.add('animate-ping-once');
                    setTimeout(() => icon.classList.remove('animate-ping-once'), 400);
                } else {
                    button.classList.add('text-gray-400');
                    button.classList.remove('text-red-500');
                    icon.setAttribute('fill', 'none');
                }

                button.setAttribute('title', data.in_wishlist ? 'حذف من المفضلة' : 'إضافة إلى المفضلة');

                const textSpan = button.querySelector('.wishlist-text');
                if (textSpan) {
                    textSpan.textContent = data.in_wishlist ? 'في المفضلة' : 'أضف إلى المفضلة';
                }

                if (data.in_wishlist) {
                    button.classList.add('border-red-500', 'text-red-500', 'bg-red-50');
                    button.classList.remove('border-gray-200', 'text-gray-600');
                } else {
                    button.classList.remove('border-red-500', 'text-red-500', 'bg-red-50');
                    button.classList.add('border-gray-200', 'text-gray-600');
                }

                updateWishlistCounter(data.in_wishlist);
                showToast(data.message, data.in_wishlist ? 'success' : 'info');

            } catch (error) {
                console.error('Wishlist error:', error);
                showToast('حدث خطأ، حاول مرة أخرى', 'error');
            }
        }

        function showToast(message, type = 'success') {
            document.querySelectorAll('.toast-notification').forEach(t => t.remove());

            const colors = {
                success: 'bg-zinc-900 text-white dark:bg-white dark:text-zinc-900',
                info: 'bg-zinc-900 text-white dark:bg-white dark:text-zinc-900',
                error: 'bg-red-600 text-white',
            };

            const toast = document.createElement('div');
            toast.className = `toast-notification fixed bottom-6 left-1/2 -translate-x-1/2 ${colors[type]} px-5 py-2.5 rounded-lg shadow-lg z-[9999] transition-all duration-300 opacity-0 translate-y-3`;
            toast.textContent = message;

            document.body.appendChild(toast);

            requestAnimationFrame(() => {
                toast.classList.remove('opacity-0', 'translate-y-3');
            });

            setTimeout(() => {
                toast.classList.add('opacity-0', 'translate-y-3');
                setTimeout(() => toast.remove(), 300);
            }, 2500);
        }

        function updateWishlistCounter(added) {
            const counter = document.getElementById('wishlist-counter');
            if (!counter) return;

            const current = parseInt(counter.textContent || '0');
            const newValue = added ? current + 1 : Math.max(0, current - 1);
            counter.textContent = newValue;

            if (newValue === 0) {
                counter.classList.add('hidden');
            } else {
                counter.classList.remove('hidden');
            }
        }
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('visible');
                    }
                });
            }, {
                threshold: 0.1,
                rootMargin: '0px 0px -50px 0px'
            });

            document.querySelectorAll('.animate-on-scroll').forEach(el => {
                observer.observe(el);
            });
        });
    </script>

    @auth
        <script>
            let notifInterval = null;
            let lastNotifCount = 0;

            async function loadNotifications() {
                try {
                    const response = await fetch('{{ route('notifications.unread') }}', {
                        headers: {
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': CSRF_TOKEN,
                        },
                    });
                    const data = await response.json();

                    if (data.count !== lastNotifCount) {
                        updateNotifBadge(data.count);

                        if (data.count > lastNotifCount && lastNotifCount > 0) {
                            showToast('لديك إشعار جديد', 'info');
                        }

                        lastNotifCount = data.count;
                    }

                    renderNotifications(data.notifications);
                } catch (error) {
                    console.error('Notifications error:', error);
                }
            }

            function updateNotifBadge(count) {
                const badge = document.getElementById('notifBadge');
                if (!badge) return;

                if (count > 0) {
                    badge.textContent = count > 9 ? '9+' : count;
                    badge.classList.remove('hidden');
                } else {
                    badge.classList.add('hidden');
                }
            }

            function renderNotifications(notifications) {
                const list = document.getElementById('notifList');
                if (!list) return;

                const dropdown = list.closest('[x-show]');
                const isOpen = dropdown && dropdown.style.display !== 'none';

                if (!isOpen && notifications.length > 0) {
                    window.pendingNotifications = notifications;
                    return;
                }

                if (notifications.length === 0) {
                    list.innerHTML = `
                            <div class="p-8 text-center">
                                <p class="text-[12px]" style="color: var(--text-tertiary);">لا توجد إشعارات جديدة</p>
                            </div>
                        `;
                    return;
                }

                const colorClasses = {
                    green: { bg: 'bg-green-100', text: 'text-green-600' },
                    yellow: { bg: 'bg-yellow-100', text: 'text-yellow-600' },
                    red: { bg: 'bg-red-100', text: 'text-red-600' },
                    blue: { bg: 'bg-blue-100', text: 'text-blue-600' },
                };

                list.innerHTML = notifications.map(n => {
                    const c = colorClasses[n.color] || colorClasses.blue;

                    return `
                            <a href="/notifications/${n.id}/read"
                               class="flex items-start gap-3 px-4 py-3 hover:bg-gray-50 transition-colors duration-150 border-b border-gray-100 last:border-0 fade-in-notif">
                                <div class="w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0 ${c.bg} ${c.text}">
                                    ${n.icon}
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-[13px] font-semibold mb-0.5 truncate" style="color: var(--text-primary);">${n.title}</p>
                                    <p class="text-[12px] mb-1 line-clamp-2" style="color: var(--text-secondary);">${n.message}</p>
                                    <p class="text-[11px]" style="color: var(--text-tertiary);">${n.created_at}</p>
                                </div>
                            </a>
                        `;
                }).join('');
            }

            function startNotifPolling() {
                if (notifInterval) return;
                notifInterval = setInterval(loadNotifications, 5000);
            }

            function stopNotifPolling() {
                if (notifInterval) {
                    clearInterval(notifInterval);
                    notifInterval = null;
                }
            }

            document.addEventListener('DOMContentLoaded', function () {
                loadNotifications();
                startNotifPolling();
                window.addEventListener('focus', loadNotifications);
                document.addEventListener('visibilitychange', function () {
                    if (!document.hidden) {
                        loadNotifications();
                        startNotifPolling();
                    } else {
                        stopNotifPolling();
                    }
                });
            });
        </script>
    @endauth

    @if($siteSettings->whatsapp)
        <a href="https://wa.me/{{ $siteSettings->whatsapp }}?text={{ urlencode('مرحباً، أرغب في الاستفسار') }}"
            target="_blank" rel="noopener noreferrer" id="whatsappBtn" title="تواصل معنا"
            class="fixed bottom-6 left-6 z-40 w-12 h-12 bg-[#25D366] hover:bg-[#1EBE5A] text-white rounded-full flex items-center justify-center shadow-lg hover:scale-105 transition-all duration-200">
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                <path
                    d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z" />
            </svg>
        </a>
    @endif

    <script>
        function copyProductLink(button) {
            const url = button.dataset.url;
            const textEl = button.querySelector('.copy-text');

            navigator.clipboard.writeText(url).then(() => {
                const originalText = textEl.textContent;
                textEl.textContent = 'تم النسخ';
                textEl.classList.add('text-green-600', 'font-semibold');

                showToast('تم نسخ الرابط بنجاح', 'success');

                setTimeout(() => {
                    textEl.textContent = originalText;
                    textEl.classList.remove('text-green-600', 'font-semibold');
                }, 2000);
            }).catch(err => {
                console.error('Copy failed:', err);
                showToast('فشل نسخ الرابط', 'error');
            });
        }
    </script>

    <script>
        async function submitNewsletter(event) {
            event.preventDefault();

            const form = event.target;
            const emailInput = document.getElementById('newsletterEmail');
            const btn = document.getElementById('newsletterBtn');
            const btnText = document.getElementById('newsletterBtnText');
            const spinner = document.getElementById('newsletterSpinner');
            const messageBox = document.getElementById('newsletterMessage');
            const email = emailInput.value.trim();

            if (!email) {
                showNewsletterMessage('الرجاء إدخال البريد الإلكتروني', 'error');
                return false;
            }

            btn.disabled = true;
            btnText.textContent = 'جاري...';
            spinner.classList.remove('hidden');
            messageBox.classList.add('hidden');

            try {
                const response = await fetch('{{ route('newsletter.subscribe') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({ email: email }),
                });

                const data = await response.json();

                btn.disabled = false;
                btnText.textContent = 'اشترك';
                spinner.classList.add('hidden');

                showNewsletterMessage(data.message, data.type || (data.success ? 'success' : 'error'));

                if (data.success) {
                    emailInput.value = '';

                    if (typeof showToast === 'function') {
                        showToast(data.message, data.type === 'info' ? 'info' : 'success');
                    }
                }

            } catch (error) {
                console.error('Newsletter error:', error);
                btn.disabled = false;
                btnText.textContent = 'اشترك';
                spinner.classList.add('hidden');
                showNewsletterMessage('حدث خطأ، حاول مرة أخرى', 'error');
            }

            return false;
        }

        function showNewsletterMessage(message, type = 'success') {
            const messageBox = document.getElementById('newsletterMessage');
            if (!messageBox) return;

            const colors = {
                success: 'bg-white text-green-700 border-2 border-green-300',
                error: 'bg-white text-red-700 border-2 border-red-300',
                info: 'bg-white text-blue-700 border-2 border-blue-300',
            };

            messageBox.className = 'max-w-md mx-auto mt-4 px-4 py-3 rounded-lg text-[13px] font-medium transition-all duration-200 ' + (colors[type] || colors.success);
            messageBox.textContent = message;
            messageBox.classList.remove('hidden');
        }
    </script>

    <script>
        function toggleDarkMode() {
            const html = document.documentElement;
            const isDark = html.classList.contains('dark');

            if (isDark) {
                html.classList.remove('dark');
                localStorage.setItem('darkMode', 'false');
                updateDarkModeIcons(false);
            } else {
                html.classList.add('dark');
                localStorage.setItem('darkMode', 'true');
                updateDarkModeIcons(true);
            }
        }

        function updateDarkModeIcons(isDark) {
            const moon = document.getElementById('darkIconMoon');
            const sun = document.getElementById('darkIconSun');

            if (!moon || !sun) return;

            if (isDark) {
                moon.classList.add('hidden');
                sun.classList.remove('hidden');
            } else {
                moon.classList.remove('hidden');
                sun.classList.add('hidden');
            }
        }

        document.addEventListener('DOMContentLoaded', function () {
            const isDark = document.documentElement.classList.contains('dark');
            updateDarkModeIcons(isDark);
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('.product-image-stack').forEach(function (stack) {
                const images = stack.querySelectorAll('.product-stack-img');
                const dots = stack.querySelectorAll('.image-dot');

                if (images.length < 2) return;

                let currentIndex = 0;
                let interval = null;

                images.forEach(function (img, i) {
                    img.style.transform = `translate(${i * 4}px, ${i * 4}px) rotate(${i * 1.5}deg)`;
                    img.style.opacity = i < 3 ? '1' : '0';
                    img.style.pointerEvents = 'none';
                });

                function showImage(index) {
                    images.forEach(function (img, i) {
                        const offset = (i - index + images.length) % images.length;

                        if (offset < 3) {
                            img.style.zIndex = 3 - offset;
                            img.style.opacity = '1';
                            img.style.transform = `translate(${offset * 4}px, ${offset * 4}px) rotate(${offset * 1.5}deg) scale(1)`;
                        } else {
                            img.style.zIndex = 0;
                            img.style.opacity = '0';
                            img.style.transform = `translate(0, 0) rotate(0deg) scale(0.9)`;
                        }
                    });

                    dots.forEach(function (dot, i) {
                        if (i === index) {
                            dot.classList.add('active');
                        } else {
                            dot.classList.remove('active');
                        }
                    });
                }

                stack.addEventListener('mouseenter', function () {
                    if (images.length < 2) return;

                    interval = setInterval(function () {
                        currentIndex = (currentIndex + 1) % images.length;
                        showImage(currentIndex);
                    }, 900);
                });

                stack.addEventListener('mouseleave', function () {
                    clearInterval(interval);
                    currentIndex = 0;
                    showImage(0);
                });

                showImage(0);
            });
        });
    </script>

</body>

</html>