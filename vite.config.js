import { defineConfig } from 'vite';
import laravel, {refreshPaths} from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/css/client/client.css',
                'resources/css/filament/admin/theme.css',
                'resources/js/app.js',
                'resources/js/driver.js',
                'resources/js/customer.js',
                'resources/js/pusher.js',
            ],
            refresh: [
                ...refreshPaths,
                'app/Livewire/**',
            ],
        }),
    ],
});
