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
                // Vurğu rəngi — açıq mavi
                accent: {
                    50: '#f0f9ff',
                    100: '#e0f2fe',
                    200: '#bae6fd',
                    300: '#7dd3fc',
                    400: '#38bdf8',
                    500: '#0ea5e9',
                    600: '#0284c7',
                    700: '#0369a1',
                    800: '#075985',
                },
            },
        },
    },
    plugins: [forms],
};
