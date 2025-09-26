import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'plugins/DixlaseInquiry/resources/src/js/app.js',
                'plugins/DixlaseInquiry/resources/src/css/style.css',
            ],
            refresh: true,
        }),
    ],
    build: {
        outDir: 'plugins/DixlaseInquiry/resources/assets',
    },
});