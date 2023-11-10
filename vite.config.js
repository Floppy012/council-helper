import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import livewire from '@defstudio/vite-livewire-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/js/app.ts'],
            refresh: false,
        }),

        livewire({
            refresh: ['resources/css/tailwind.css'],
        })
    ],
    // server: {
    //     hmr: {
    //         host: 'localhost'
    //     }
    // }
});
