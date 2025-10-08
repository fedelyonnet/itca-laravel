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
});
