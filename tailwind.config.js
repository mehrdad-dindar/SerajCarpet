import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';
import typography from '@tailwindcss/typography';
import preset from './vendor/filament/support/tailwind.config.preset'
import themer from "@tailus/themer";

/** @type {import('tailwindcss').Config} */
export default {
    presets: [preset],
        content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './vendor/laravel/jetstream/**/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './vendor/awcodes/overlook/resources/**/*.blade.php',
        './app/Filament/**/*.php',
        './resources/views/filament/**/*.blade.php',
        './vendor/filament/**/*.blade.php',
        ],

        theme: {
            extend: {
                fontFamily: {
                    sans: ['Figtree', ...defaultTheme.fontFamily.sans],
                    iranSans : ['IRANSansX', ...defaultTheme.fontFamily.sans],
                },
                keyframes: {
                    loop: {
                        to: {
                            "offset-distance": "100%",
                        },
                    },
                },
                backgroundImage: {
                    'gradient-radial': 'radial-gradient(circle, rgba(22,96,159,1) 6%, rgba(31,45,81,1) 38%, rgba(9,8,22,1) 77%)',
                }
            },
    },

    plugins: [
        forms,
        typography,
        themer({
            palette: {
                extend : "nature"
            },
            radius: "smoothest",
            background: "light",
            border: "light",
            padding:"large",
            components: {
                button: {
                    rounded : "2xl"
                }
            }
        })
    ],
    };
