import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css', 
                'resources/css/backoffice.css',
                'resources/css/public.css',
                'resources/js/app.js',
                'resources/js/backoffice.js'
            ],
            refresh: true,
        }),
    ],
    server: {
        cors: true,
        headers: {
            'Access-Control-Allow-Origin': '*',
            'Access-Control-Allow-Methods': 'GET, POST, PUT, DELETE, OPTIONS',
            'Access-Control-Allow-Headers': 'Content-Type, Authorization',
        },
        proxy: {
            '/images': {
                target: 'http://devitca.localhost',
                changeOrigin: true,
                secure: false,
            },
        },
    },
});
