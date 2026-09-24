import Echo from 'laravel-echo';
import Pusher from 'pusher-js';
import { Livewire, Alpine } from '../../vendor/livewire/livewire/dist/livewire.esm';

window.Pusher = Pusher;

// Laravel Echo with Reverb
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
    console.log('Laravel Echo initialized');
} catch (error) {
    console.error('Broadcast server connection failed:', error);
}

window.Alpine = Alpine;
window.Livewire = Livewire;

// Livewire 3 يتولى تشغيل Alpine داخلياً