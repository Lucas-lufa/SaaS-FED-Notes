 /*
 * Filename: tailwind.config.js
 * Location: /
 */

/** @type {import('tailwindcss').Config} */
module.exports = {
    content: [
        "./src/**/*.{html,js}",
        "./7/{App, public, config, Framework}/**/*.{html,js,php}"
    ],
    theme: {
        extend: {},
    },
    plugins: [],
}