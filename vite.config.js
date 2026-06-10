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
                'resources/js/password_setup.js',
                'resources/js/turbo-navigation.js',
                'resources/js/items-page.js'
            ],
            refresh: true,
        }),
        tailwindcss(),
    ],
    server: {
        // Fix HMR WebSocket URL: prevents malformed URLs like ws://http//127.0.0.1:8000//ws
        // Forces Vite to use 'localhost' as HMR hostname instead of deriving from APP_URL
        hmr: {
            host: 'localhost',
        },
        host: 'localhost',
        watch: {
            ignored: ['**/storage/framework/views/**'],
        },
    },
});
