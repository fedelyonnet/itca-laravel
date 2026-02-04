import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/css/backoffice.css',
                'resources/css/public.css',
                'resources/css/somos-itca.css',
                'resources/css/beneficios.css',
                'resources/css/inscripcion-mobile.css',
                'resources/js/app.js',
                'resources/js/backoffice.js',
                'resources/js/carreras-test.js',
                'resources/js/importacion-cursos.js',
                'resources/js/importacion-promociones.js',
                'resources/css/ret-mp.css',
                'resources/js/inscripcion.js'
            ],
            refresh: true,
        }),
    ],
    // Configurar para que Vite reconozca las imágenes en public/ como recursos válidos
    publicDir: 'public',
    build: {
        // Suprimir warnings sobre rutas que no se resuelven en build time
        // Estas rutas se resuelven correctamente en runtime desde public/
        rollupOptions: {
            onwarn(warning, warn) {
                // Ignorar warnings sobre imágenes en public/ que no se resuelven en build time
                // Estos warnings son normales cuando usas rutas absolutas a archivos en public/
                if (warning.message && warning.message.includes("didn't resolve at build time")) {
                    if (warning.message.includes('/images/')) {
                        return; // Suprimir estos warnings específicos
                    }
                }
                // Mostrar otros warnings normalmente
                warn(warning);
            },
        },
    },
    server: {
        host: '0.0.0.0', // Permite conexiones desde cualquier IP
        port: 5173,
        strictPort: true,
        cors: true,
        headers: {
            'Access-Control-Allow-Origin': '*',
            'Access-Control-Allow-Methods': 'GET, POST, PUT, DELETE, OPTIONS',
            'Access-Control-Allow-Headers': 'Content-Type, Authorization',
        },
        hmr: {
            host: 'localhost',
            protocol: 'ws',
            clientPort: 5173,
            overlay: false, // Desactiva el overlay de errores en desarrollo
        },
        watch: {
            // Reduce la frecuencia de actualizaciones
            usePolling: false,
            interval: 1000,
        },
    },
});
