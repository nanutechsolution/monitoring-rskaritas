import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    // 1. Mode Gelap (dari file Anda)
    darkMode: 'class',
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            // 3. Font Family (dari file Anda)
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },

            gridTemplateColumns: {
                '25': 'repeat(25, minmax(0, 1fr))',
            },

            // 5. TAMBAHAN: Palet Warna Tema Karitas
            colors: {
                'karitas-blue': {
                    '50': '#eef6ff',
                    '100': '#d9ecff',
                    '200': '#baddff',
                    '300': '#8ccaff',
                    '400': '#59b0ff',
                    '500': '#3691fa',
                    '600': '#1b72e7',
                    '700': '#135bc9',
                    '800': '#144ba7',
                    '900': '#174086', // Biru paling gelap, mirip logo
                    '950': '#102a54',
                },
                'karitas-red': {
                    '50': '#fef2f2',
                    '100': '#fee2e2',
                    '200': '#fecaca',
                    '300': '#fca5a5',
                    '400': '#f87171',
                    '500': '#ef4444',
                    '600': '#dc2626', // Merah palang, mirip logo
                    '700': '#b91c1c',
                    '800': '#991b1b',
                    '900': '#7f1d1d',
                    '950': '#450a0a',
                },

                // 6. TAMBAHAN: Alias Semantik (untuk Tombol & Aksen)
                'primary': {
                    '50': '#eef6ff',
                    '100': '#d9ecff',
                    '200': '#baddff',
                    '300': '#8ccaff',
                    '400': '#59b0ff',
                    '500': '#3691fa',
                    '600': '#1b72e7', // <- Warna tombol utama
                    '700': '#135bc9', // <- Warna hover
                    '800': '#144ba7',
                    '900': '#174086',
                    '950': '#102a54',
                },
                'danger': {
                    '50': '#fef2f2',
                    '100': '#fee2e2',
                    '200': '#fecaca',
                    '300': '#fca5a5',
                    '400': '#f87171',
                    '500': '#ef4444',
                    '600': '#dc2626', // <- Warna tombol danger
                    '700': '#b91c1c', // <- Warna hover
                    '800': '#991b1b',
                    '900': '#7f1d1d',
                    '950': '#450a0a',

                },

            }
        },
    },

    // 7. Plugins (dari file Anda)
    plugins: [forms],
};
