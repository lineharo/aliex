import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/admin.sass',
                'resources/css/front.sass',
                'resources/js/app.js',
            ],
            refresh: true,
        }),
    ],
});
