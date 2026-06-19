import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',                            // Tailwind
                'resources/scss/app.scss',                          // SCSS architecture (CMS)
                'resources/scss/website/app.scss',                  // Public website
                'resources/scss/certificates/baptism_print.scss',   // Standalone print stylesheet
                'resources/scss/certificates/dedication_print.scss', // Dedication certificate print
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
