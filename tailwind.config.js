import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

export default {
    darkMode: 'class',
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],
    theme: {
        extend: {
            fontFamily: {
                sans: ['Cairo', ...defaultTheme.fontFamily.sans],
                display: ['IBM Plex Sans Arabic', 'Cairo', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                forest: { DEFAULT: '#005a96', deep: '#003d66', soft: '#2a7ab5', mist: '#e6f1f8' },
                gold: { DEFAULT: '#14b8a6', light: '#5eead4', deep: '#0f766e' },
                cream: { DEFAULT: '#FAFAF9', soft: '#F5F5F4', warm: '#EFEEEC' },
                ink: { DEFAULT: '#0F0F0F', soft: '#2A2A2A', muted: '#6B6B5E', faint: '#9A9A8C' },
            },
            fontSize: {
                '8xl': ['6rem', { lineHeight: '1', letterSpacing: '-0.03em' }],
                '9xl': ['8rem', { lineHeight: '0.95', letterSpacing: '-0.04em' }],
                '10xl': ['11rem', { lineHeight: '0.9', letterSpacing: '-0.05em' }],
            },
            animation: {
                'marquee': 'marquee 40s linear infinite',
                'reveal': 'reveal 0.9s cubic-bezier(0.16, 1, 0.3, 1) both',
            },
            keyframes: {
                marquee: { '0%': { transform: 'translateX(0)' }, '100%': { transform: 'translateX(-50%)' } },
                reveal: { '0%': { opacity: '0', transform: 'translateY(32px)' }, '100%': { opacity: '1', transform: 'translateY(0)' } },
            },
        },
    },
    plugins: [forms],
};