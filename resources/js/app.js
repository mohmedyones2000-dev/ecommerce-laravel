import Echo from 'laravel-echo';
import Pusher from 'pusher-js';
import Alpine from 'alpinejs';

window.Pusher = Pusher;

// ✅ Laravel Echo with Reverb
try {
    window.Echo = new Echo({
        broadcaster: 'reverb',
        key: import.meta.env.VITE_REVERB_APP_KEY,
        wsHost: import.meta.env.VITE_REVERB_HOST,
        wsPort: import.meta.env.VITE_REVERB_PORT ?? 80,
        wssPort: import.meta.env.VITE_REVERB_PORT ?? 443,
        forceTLS: (import.meta.env.VITE_REVERB_SCHEME ?? 'https') === 'https',
        enabledTransports: ['ws', 'wss'],
        authEndpoint: '/broadcasting/auth',
    });

    console.log('✅ Laravel Echo initialized');
} catch (error) {
    console.error('❌ Broadcast server connection failed:', error);
    fetch('/api/log-broadcast-error', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content,
        },
        body: JSON.stringify({ error: error.message }),
    }).catch(() => {});
}

window.Alpine = Alpine;
Alpine.start();

// ✅ الاستماع للإشعارات اللحظية
document.addEventListener('DOMContentLoaded', () => {
    if (!window.Echo || !window.userId) {
        console.log('⚠️ Echo not ready or user not logged in');
        return;
    }

    console.log('🎧 Listening for user:', window.userId);

    window.Echo.private(`App.Models.User.${window.userId}`)
        .notification((n) => {
            console.log('🔔 User notification:', n);
            handleIncomingNotification(n);
        });

    if (window.userRole === 'admin' || window.userRole === 'manager') {
        console.log('🎧 Listening on admin.notifications');
        window.Echo.private('admin.notifications')
            .notification((n) => {
                console.log('🔔 Admin notification:', n);
                handleIncomingNotification(n);
            });
    }
});

function handleIncomingNotification(notification) {
    updateNotificationBadge();
    const title = notification.title || 'إشعار جديد';
    const message = notification.message || notification.body || '';
    const type = notification.color === 'danger' ? 'error'
              : notification.color === 'warning' ? 'warning'
              : notification.color === 'success' ? 'success' : 'info';
    showToast(`${title}: ${message}`, type);
    prependNotificationToList(notification);
}

function updateNotificationBadge() {
    const badge = document.getElementById('notifBadge');
    if (!badge) return;
    const current = parseInt(badge.textContent) || 0;
    const newCount = current + 1;
    badge.textContent = newCount > 9 ? '9+' : newCount;
    badge.classList.remove('hidden');
    badge.classList.add('animate-ping-once');
    setTimeout(() => badge.classList.remove('animate-ping-once'), 500);
}

function prependNotificationToList(notification) {
    const list = document.getElementById('notifList');
    if (!list) return;

    const emptyState = list.querySelector('.p-8');
    if (emptyState) emptyState.remove();

    const colorClasses = {
        green:  { bg: 'bg-green-100',  text: 'text-green-600' },
        yellow: { bg: 'bg-yellow-100', text: 'text-yellow-600' },
        red:    { bg: 'bg-red-100',    text: 'text-red-600' },
        blue:   { bg: 'bg-blue-100',   text: 'text-blue-600' },
    };
    const color = colorClasses[notification.color] || colorClasses.blue;

    list.insertAdjacentHTML('afterbegin', `
        <a href="${notification.url || '#'}"
           class="flex items-start gap-3 px-4 py-3 hover:bg-gray-50 transition-colors border-b border-gray-100 last:border-0 fade-in-notif">
            <div class="w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0 ${color.bg} ${color.text}">
                🔔
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-[13px] font-semibold mb-0.5 truncate" style="color: var(--text-primary);">
                    ${notification.title || 'إشعار'}
                </p>
                <p class="text-[12px] mb-1 line-clamp-2" style="color: var(--text-secondary);">
                    ${notification.message || notification.body || ''}
                </p>
                <p class="text-[11px]" style="color: var(--text-tertiary);">الآن</p>
            </div>
        </a>
    `);
}

window.showToast = window.showToast || function (message, type = 'info') {
    const colors = {
        success: 'bg-green-600 text-white',
        error:   'bg-red-600 text-white',
        warning: 'bg-yellow-500 text-white',
        info:    'bg-blue-600 text-white',
    };
    const toast = document.createElement('div');
    toast.className = `fixed top-20 left-1/2 -translate-x-1/2 ${colors[type]} px-6 py-3 rounded-lg shadow-lg z-[9999] transition-all duration-300 opacity-0 translate-y-3`;
    toast.textContent = message;
    document.body.appendChild(toast);
    requestAnimationFrame(() => toast.classList.remove('opacity-0', 'translate-y-3'));
    setTimeout(() => {
        toast.classList.add('opacity-0', 'translate-y-3');
        setTimeout(() => toast.remove(), 300);
    }, 4000);
};