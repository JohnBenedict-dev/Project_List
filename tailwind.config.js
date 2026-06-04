import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.jsx',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                // New Primary Blue color scheme for the sidebar and main elements
                primary: {
                    DEFAULT: '#3B82F6', // A vibrant, clean blue
                    light: '#DBEAFE', // Light blue background for active menu/card accents
                    dark: '#1D4ED8',  // For hover states or deep accents
                },
                secondary: {
                    DEFAULT: '#111827', // Keep the dark grey for card titles
                },
                accent: {
                    DEFAULT: '#3B82F6', // Match the donut chart and buttons
                },
            },
        },
    },

    plugins: [forms],
};