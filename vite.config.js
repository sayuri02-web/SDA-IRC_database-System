import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',                       // Tailwind
                'resources/scss/app.scss',                     // SCSS architecture
                'resources/scss/website/app.scss',             // Public website
                'resources/css/certificates/baptism_print.css', // Standalone print stylesheet
                'resources/js/app.js',
            ],
            refresh: true,
        }),
        tailwindcss(),
    ],
    server: {
        watch: {
            ignored: ['**/storage/framework/views/**'],
        },
    },
});
