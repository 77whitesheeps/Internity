import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: [
                '../templates/**/*.blade.php',
                '../static/css/**/*.css',
                '../static/js/**/*.js',
                'routes/**/*.php',
            ],
        }),
    ],
    server: {
        fs: {
            allow: ['..'],
        },
        watch: {
            ignored: ['**/storage/framework/views/**'],
        },
    },
});
