import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.vue',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Open Sans', ...defaultTheme.fontFamily.sans],
                heading: ['Montserrat', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                red: {
                    DEFAULT: '#F05423',
                    light: '#F57B56',
                    dark: '#C8441D',
                    bright: '#FF6B3D',
                },
                charcoal: {
                    DEFAULT: '#1a1a1a',
                    light: '#2d2d2d',
                    dark: '#0d0d0d',
                },
            },
        },
    },


    plugins: [forms],
};
