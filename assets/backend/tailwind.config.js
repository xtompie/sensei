/** @type {import('tailwindcss').Config} */
module.exports = {
    content: ["../../src/Backend/**/*.tpl.php"],
    theme: {
        extend: {
        },
    },
    plugins: [
        require('@tailwindcss/forms'),
    ]
}