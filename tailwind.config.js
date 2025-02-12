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
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                primary: "#3B195C",
                primary_var: "#5d2892",
                primary_var2: "#6d478f",
                secondary: '#3A5C19',
                secondary_var: '#206f37',
                secondary_var2: '#00825c',
                secondary_var3: '#97f8ad',
            }
        },
    },

    plugins: [forms],
};
