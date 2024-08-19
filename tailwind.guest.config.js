const defaultTheme = require('tailwindcss/defaultTheme');
const colors = require('tailwindcss/colors')

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
                urbanist: ['Urbanist']
            },
        },
    },
};
