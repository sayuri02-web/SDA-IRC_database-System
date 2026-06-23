import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';
import vue from '@vitejs/plugin-vue';

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
                'resources/js/dashboard.js',                        // Vue Dashboard
                'resources/js/website.js',                          // Vue Website (Login Modal)
            ],
            refresh: true,
        }),
        tailwindcss(),
        vue({
            template: {
                transformAssetUrls: {
                    base: null,
                    includeAbsolute: false,
                },
            },
        }),
    ],
    resolve: {
        alias: {
            vue: 'vue/dist/vue.esm-bundler.js',
        },
    },
    server: {
        watch: {
            ignored: ['**/storage/framework/views/**'],
        },
    },
});
