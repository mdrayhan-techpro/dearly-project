import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';
import typography from '@tailwindcss/typography';

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
            colors: {
                dearly: {
                    ivory: '#F6F0E5',
                    aged: '#E9DDC7',
                    parchment: '#F0E6D2',
                    ink: '#342A25',
                    'ink-soft': '#5A4A42',
                    burgundy: '#783C46',
                    'burgundy-dark': '#582A32',
                    forest: '#405B4B',
                    'forest-dark': '#2D4135',
                    brass: '#B79A61',
                    'brass-dark': '#937946',
                    blue: '#778B99',
                    rose: '#C58E8B',
                }
            },
            fontFamily: {
                serif: ['Playfair Display', 'Merriweather', 'Georgia', ...defaultTheme.fontFamily.serif],
                handwriting: ['Caveat', 'Dancing Script', 'cursive'],
                sans: ['Inter', 'Outfit', 'Segoe UI', ...defaultTheme.fontFamily.sans],
            },
            boxShadow: {
                'paper': '0 4px 20px -2px rgba(52, 42, 37, 0.12), 0 2px 6px -1px rgba(52, 42, 37, 0.08)',
                'envelope': '0 10px 30px -4px rgba(52, 42, 37, 0.25), 0 4px 10px -2px rgba(52, 42, 37, 0.15)',
                'seal': '0 4px 12px rgba(120, 60, 70, 0.35)',
            }
        },
    },
    plugins: [forms, typography],
};
