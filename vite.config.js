import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
   server: {
        host: true,
        hmr: {
            host: 'localhost',
        },
    },
    plugins: [
        laravel({
            input: [
                'resources/assets/admin/sass/app.scss',
                'resources/assets/admin/js/app.js'
            ],
            refresh: true,
        }),
        tailwindcss(),
    ],
});
