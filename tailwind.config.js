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
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
                display: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                brand: {
                    50: '#eef7f6',
                    100: '#d5ece9',
                    200: '#aed9d3',
                    300: '#7fbfb7',
                    400: '#529f96',
                    500: '#37827a',
                    600: '#2b6962',
                    700: '#255550',
                    800: '#214543',
                    900: '#1e3a39',
                    950: '#0c201f',
                },
                accent: {
                    50: '#fffbeb',
                    100: '#fef3c7',
                    400: '#fbbf24',
                    500: '#f59e0b',
                    600: '#d97706',
                },
            },
        },
    },

    plugins: [forms],
};
