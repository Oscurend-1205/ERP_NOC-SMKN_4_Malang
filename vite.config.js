import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css', 
                'resources/css/dashboard.css', 
                'resources/css/login.css', 
                'resources/css/password_setup.css',
                'resources/js/app.js',
                'resources/js/login.js',
                'resources/js/dashboard.js',
                'resources/js/app_layout.js',
                'resources/js/password_setup.js'
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
