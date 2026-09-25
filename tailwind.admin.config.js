const defaultTheme = require('tailwindcss/defaultTheme');

/** @type {import('tailwindcss').Config} */
module.exports = {
    presets: [
        require('./tailwind.config.js')
    ],
    theme: {
        extend: {
            colors: {
                slate: {
                    850: '#181f34',
                }
            },
            fontFamily: {
                sans: ['Urbanist', ...defaultTheme.fontFamily.sans],
            },
            boxShadow: {
                'accent-sm': '0 2px 8px -2px rgb(241 89 143 / 0.35)',
            },
        },
    },
};