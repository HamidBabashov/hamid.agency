import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    darkMode: 'class',
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/**/*.blade.php',
        './resources/**/*.js',
    ],
    theme: {
        container: {
            center: true,
            padding: { DEFAULT: '1rem', sm: '1.5rem', lg: '2rem' },
            screens: { '2xl': '1280px' },
        },
        extend: {
            fontFamily: {
                sans: ['Inter', ...defaultTheme.fontFamily.sans],
            },
            keyframes: {
                aurora: {
                    '0%, 100%': { transform: 'translate3d(0,0,0) scale(1)' },
                    '33%': { transform: 'translate3d(6%,-8%,0) scale(1.1)' },
                    '66%': { transform: 'translate3d(-6%,6%,0) scale(.95)' },
                },
                float: {
                    '0%, 100%': { transform: 'translateY(0)' },
                    '50%': { transform: 'translateY(-10px)' },
                },
                marquee: {
                    from: { transform: 'translateX(0)' },
                    to: { transform: 'translateX(-50%)' },
                },
                shimmer: {
                    from: { backgroundPosition: '200% 0' },
                    to: { backgroundPosition: '-200% 0' },
                },
            },
            animation: {
                aurora: 'aurora 18s ease-in-out infinite',
                'aurora-slow': 'aurora 26s ease-in-out infinite reverse',
                float: 'float 6s ease-in-out infinite',
                'float-delayed': 'float 7s ease-in-out 1.5s infinite',
                marquee: 'marquee 40s linear infinite',
                shimmer: 'shimmer 6s linear infinite',
            },
            boxShadow: {
                soft: '0 1px 2px rgba(15,23,42,.04), 0 12px 32px -16px rgba(15,23,42,.18)',
                glow: '0 0 0 1px rgba(255,255,255,.06), 0 20px 60px -20px rgba(52,102,246,.45)',
            },
            colors: {
                ink: '#050b1a',
                // Əsas rəng — tünd göy
                brand: {
                    50: '#eef4ff',
                    100: '#dae6ff',
                    200: '#bdd3ff',
                    300: '#8fb6ff',
                    400: '#5a8dfc',
                    500: '#3466f6',
                    600: '#1e47eb',
                    700: '#1834c8',
                    800: '#1a2ea2',
                    900: '#1b2c80',
                    950: '#0b1f3a',
                },
                // Vurğu rəngi — kəhrəba
                accent: {
                    50: '#fffbeb',
                    100: '#fef3c7',
                    200: '#fde68a',
                    300: '#fcd34d',
                    400: '#fbbf24',
                    500: '#f59e0b',
                    600: '#d97706',
                    700: '#b45309',
                },
            },
        },
    },
    plugins: [forms],
};
