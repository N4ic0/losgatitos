import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.js',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                gold: {
                    DEFAULT: '#D4AF37',
                    50: '#FDF8E8',
                    100: '#F9EBBF',
                    200: '#F0D88A',
                    300: '#E4C254',
                    400: '#D4AF37',
                    500: '#C49A2C',
                    600: '#A47E22',
                    700: '#84641A',
                    800: '#644B14',
                    900: '#44320E',
                },
                dark: {
                    DEFAULT: '#0a0a0a',
                    50: '#1a1a2e',
                    100: '#16162a',
                    200: '#111125',
                    300: '#0d0d1f',
                    400: '#0a0a1a',
                    500: '#070715',
                    600: '#050510',
                    700: '#03030a',
                    800: '#010105',
                    900: '#000000',
                },
            },
        },
    },

    plugins: [forms],
};
