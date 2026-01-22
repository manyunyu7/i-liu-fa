import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Nunito', 'Figtree', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                // Duolingo-inspired color palette
                duo: {
                    green: '#58CC02',
                    'green-dark': '#58A700',
                    'green-light': '#89E219',
                    blue: '#1CB0F6',
                    'blue-dark': '#1899D6',
                    purple: '#CE82FF',
                    'purple-dark': '#9B51E0',
                    red: '#FF4B4B',
                    'red-dark': '#EA2B2B',
                    orange: '#FF9600',
                    'orange-dark': '#E08000',
                    yellow: '#FFC800',
                    pink: '#FF86D0',
                    gray: {
                        50: '#F7F7F7',
                        100: '#E5E5E5',
                        200: '#AFAFAF',
                        300: '#777777',
                        400: '#4B4B4B',
                        500: '#3C3C3C',
                    },
                },
            },
            borderRadius: {
                'duo': '16px',
                'duo-lg': '20px',
                'duo-xl': '24px',
            },
            boxShadow: {
                'duo': '0 2px 4px rgba(0,0,0,0.05)',
                'duo-md': '0 4px 8px rgba(0,0,0,0.1)',
                'duo-lg': '0 8px 16px rgba(0,0,0,0.15)',
                'duo-btn': '0 4px 0 0',
            },
            animation: {
                'bounce-slow': 'bounce 2s infinite',
                'pulse-slow': 'pulse 3s infinite',
                'confetti': 'confetti 1s ease-out forwards',
                'xp-float': 'xpFloat 1s ease-out forwards',
                'celebrate': 'celebrate 0.5s ease-out',
            },
            keyframes: {
                confetti: {
                    '0%': { transform: 'translateY(0) rotate(0)', opacity: '1' },
                    '100%': { transform: 'translateY(-100vh) rotate(720deg)', opacity: '0' },
                },
                xpFloat: {
                    '0%': { transform: 'translateY(0)', opacity: '1' },
                    '100%': { transform: 'translateY(-50px)', opacity: '0' },
                },
                celebrate: {
                    '0%, 100%': { transform: 'scale(1)' },
                    '50%': { transform: 'scale(1.1)' },
                },
            },
        },
    },

    plugins: [forms],
};
