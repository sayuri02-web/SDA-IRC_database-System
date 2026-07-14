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
                'resources/scss/certificates/membership_print.scss', // Membership certificate print
                'resources/scss/certificates/counseling_print.scss', // Counseling certificate print
                'resources/scss/certificates/studentcert.scss',      // Student certificate print
                'resources/scss/certificates/goodmoralcert.scss',    // Good Moral certificate print
                'resources/scss/certificates/membersaffiliate.scss', // Members Affiliate certificate print
                'resources/js/app.js',
                'resources/js/dashboard.js',                        // Vue Dashboard
                'resources/js/website.js',                          // Vue Website (Login Modal)
                'resources/js/about-management.js',                  // About Us Management

                'resources/js/member/edit.js',
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
