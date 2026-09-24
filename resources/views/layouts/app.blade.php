<!DOCTYPE html>
<html lang="ar" dir="rtl" class="scroll-smooth">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', $siteSettings->site_name ?? 'متجري')</title>

    @if($siteSettings->favicon)
        <link rel="icon" href="{{ asset('storage/' . $siteSettings->favicon) }}">
    @else
        <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    @endif

    <meta name="theme-color" content="#14b8a6">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;500;600;700;800&family=IBM+Plex+Sans+Arabic:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <script>
        window.userId = {{ auth()->id() ?? 'null' }};
        window.userRole = @json(auth()->user()?->role ?? '');
        (function () {
            const saved = localStorage.getItem('darkMode');
            const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
            if (saved === 'true' || (saved === null && prefersDark)) document.documentElement.classList.add('dark');
        })();
    </script>
</head>

<body class="min-h-screen flex flex-col bg-canvas text-ink dark:text-cream">

    {{-- Marquee — BLUE bar --}}
    <div class="bg-forest text-cream overflow-hidden py-3 relative z-40">
        <div class="flex animate-marquee whitespace-nowrap">
            @for($i = 0; $i < 2; $i++)
                <div class="flex shrink-0 items-center gap-12 px-6 text-xs font-medium tracking-[0.15em] uppercase">
                    <span class="flex items-center gap-3"><span class="w-1 h-1 rounded-full bg-gold"></span>شحن مجاني
                        للطلبات فوق 200$</span>
                    <span class="flex items-center gap-3"><span class="w-1 h-1 rounded-full bg-gold"></span>إرجاع مجاني خلال
                        14 يوم</span>
                    <span class="flex items-center gap-3"><span class="w-1 h-1 rounded-full bg-gold"></span>دعم على مدار
                        الساعة</span>
                    <span class="flex items-center gap-3"><span
                            class="w-1 h-1 rounded-full bg-gold"></span>{{ $siteSettings->site_name ?? 'متجري' }}</span>
                </div>
            @endfor
        </div>
    </div>

    @include('layouts.partials.navbar')

    <main class="flex-1">@yield('content')</main>

    @include('layouts.partials.footer')

    @if($siteSettings->whatsapp)
        <a href="https://wa.me/{{ $siteSettings->whatsapp }}" target="_blank" rel="noopener"
            class="fixed bottom-6 left-6 z-40 w-12 h-12 text-white flex items-center justify-center shadow-xl transition-all duration-300 hover:-translate-y-1"
            style="border-radius: 4px; background-color: rgb(20, 184, 166);"
            onmouseover="this.style.backgroundColor='rgb(0, 90, 150)'"
            onmouseout="this.style.backgroundColor='rgb(20, 184, 166)'" title="تواصل معنا">
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                <path
                    d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z" />
            </svg>
        </a>
    @endif

    <script>
        const CSRF_TOKEN = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        function showToast(message, type = 'success') {
            document.querySelectorAll('.toast-notification').forEach(t => t.remove());
            const toast = document.createElement('div');
            toast.className = 'toast-notification fixed bottom-6 left-1/2 -translate-x-1/2 z-[9999] px-6 py-3 text-sm font-semibold shadow-2xl transition-all duration-300 opacity-0 translate-y-3 bg-ink text-cream dark:bg-cream dark:text-ink';
            toast.style.borderRadius = '4px';
            toast.textContent = message;
            document.body.appendChild(toast);
            requestAnimationFrame(() => toast.classList.remove('opacity-0', 'translate-y-3'));
            setTimeout(() => { toast.classList.add('opacity-0', 'translate-y-3'); setTimeout(() => toast.remove(), 300); }, 2500);
        }

        async function toggleWishlist(button) {
            const productId = button.dataset.productId;
            const icon = button.querySelector('.wishlist-icon');
            try {
                const res = await fetch('{{ route('wishlist.toggle') }}', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF_TOKEN, 'Accept': 'application/json' },
                    body: JSON.stringify({ product_id: productId }),
                });
                if (res.status === 401) { window.location.href = '{{ route('login') }}'; return; }
                const data = await res.json();
                if (data.in_wishlist) { icon.setAttribute('fill', 'currentColor'); button.style.color = 'rgb(20, 184, 166)'; }
                else { icon.setAttribute('fill', 'none'); button.style.color = ''; }
                showToast(data.message, 'success');
            } catch (e) { showToast('حدث خطأ', 'error'); }
        }

        function toggleDarkMode() {
            const html = document.documentElement;
            const isDark = html.classList.contains('dark');
            html.classList.toggle('dark', !isDark);
            localStorage.setItem('darkMode', isDark ? 'false' : 'true');
            updateDarkModeIcons(!isDark);
        }
        function updateDarkModeIcons(isDark) {
            document.getElementById('darkIconMoon')?.classList.toggle('hidden', isDark);
            document.getElementById('darkIconSun')?.classList.toggle('hidden', !isDark);
        }
        document.addEventListener('DOMContentLoaded', () => updateDarkModeIcons(document.documentElement.classList.contains('dark')));

        async function submitNewsletter(event) {
            event.preventDefault();
            const emailInput = document.getElementById('newsletterEmail');
            const btn = document.getElementById('newsletterBtn');
            const btnText = document.getElementById('newsletterBtnText');
            const spinner = document.getElementById('newsletterSpinner');
            const msg = document.getElementById('newsletterMessage');
            const email = emailInput.value.trim();
            if (!email) return false;
            btn.disabled = true; btnText.textContent = 'جارٍ...';
            spinner?.classList.remove('hidden'); msg?.classList.add('hidden');
            try {
                const res = await fetch('{{ route('newsletter.subscribe') }}', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF_TOKEN, 'Accept': 'application/json' },
                    body: JSON.stringify({ email }),
                });
                const data = await res.json();
                btn.disabled = false; btnText.textContent = 'اشترك';
                spinner?.classList.add('hidden');
                if (msg) { msg.className = 'mt-4 text-sm ' + (data.success ? 'text-brand' : 'text-red-400'); msg.textContent = data.message; msg.classList.remove('hidden'); }
                if (data.success) { emailInput.value = ''; showToast(data.message, 'success'); }
            } catch (e) { btn.disabled = false; btnText.textContent = 'اشترك'; spinner?.classList.add('hidden'); showToast('حدث خطأ', 'error'); }
            return false;
        }

        (function () {
    const reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    let io = null;

    function initReveal() {
        const elements = document.querySelectorAll('.reveal:not(.is-visible)');

        if (reducedMotion) {
            elements.forEach(el => el.classList.add('is-visible'));
            return;
        }

        if (!io) {
            io = new IntersectionObserver((entries) => {
                entries.forEach(e => {
                    if (e.isIntersecting) {
                        e.target.classList.add('is-visible');
                        io.unobserve(e.target);
                    }
                });
            }, { threshold: 0.05, rootMargin: '0px 0px -40px 0px' });
        }

        elements.forEach(el => io.observe(el));
    }

    // أول تحميل
    document.addEventListener('DOMContentLoaded', initReveal);

    // بعد كل Livewire update
    document.addEventListener('livewire:navigated', initReveal);

    document.addEventListener('livewire:init', () => {
        if (window.Livewire) {
            Livewire.hook('morph.updated', () => {
                requestAnimationFrame(initReveal);
            });
        }
    });
})();
    </script>

    @auth
        <script>
            let notifInterval = null, lastNotifCount = 0;
            async function loadNotifications() {
                try {
                    const res = await fetch('{{ route('notifications.unread') }}', { headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': CSRF_TOKEN } });
                    const data = await res.json();
                    if (data.count !== lastNotifCount) {
                        const badge = document.getElementById('notifBadge');
                        if (badge) { badge.textContent = data.count > 9 ? '9+' : data.count; badge.classList.toggle('hidden', data.count === 0); }
                        lastNotifCount = data.count;
                    }
                    const list = document.getElementById('notifList');
                    if (!list) return;
                    if (!data.notifications.length) { list.innerHTML = '<div class="p-8 text-center text-xs">لا توجد إشعارات</div>'; return; }
                    list.innerHTML = data.notifications.map(n => `
                            <a href="/notifications/${n.id}/read" class="flex items-start gap-3 px-4 py-3 border-b border-gray-100 dark:border-zinc-800 last:border-0">
                                <div class="w-8 h-8 flex items-center justify-center shrink-0" style="border-radius: 4px; background: rgba(20, 184, 166, 0.1);">${n.icon}</div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-semibold truncate">${n.title}</p>
                                    <p class="text-xs opacity-70 line-clamp-2 mt-1">${n.message}</p>
                                </div>
                            </a>
                        `).join('');
                } catch (e) { }
            }
            document.addEventListener('DOMContentLoaded', () => { loadNotifications(); notifInterval = setInterval(loadNotifications, 15000); });
        </script>
    @endauth

    @stack('scripts')
</body>

</html>