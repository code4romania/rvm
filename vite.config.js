import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from "@tailwindcss/vite"

export default defineConfig({
    plugins: [
        laravel({
            refresh: true,
            detectTls: true,
            input: ['resources/css/app.css'],
            assets: ['resources/images/**'],
        }),
        tailwindcss(),
    ],
});
