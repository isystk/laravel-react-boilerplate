import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import react from '@vitejs/plugin-react';
import path from 'path';

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
                'resources/assets/front/ts/app.tsx',
                'resources/assets/front/sass/app.scss',
                'resources/assets/admin/js/app.js',
                'resources/assets/admin/sass/app.scss',
            ],
            refresh: true,
        }),
        react()
    ],
    resolve: {
        alias: {
            '@': path.resolve(__dirname, 'resources/assets/front/ts')
        }
    }
});
