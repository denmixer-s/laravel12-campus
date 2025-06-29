import {defineConfig, loadEnv} from 'vite'
import laravel from 'laravel-vite-plugin';
import tailwindcss from "@tailwindcss/vite";

export default defineConfig(({command, mode}) => {
    const env = loadEnv(mode, process.cwd(), '')

    return {
        plugins: [
            tailwindcss(),
            laravel({
                input: [
                    // Frontend assets (Livewire Components สำหรับลูกค้า)
                    'resources/css/frontend.css',
                    'resources/js/frontend.js',

                    // Backend assets (Livewire Components สำหรับ Admin)
                    'resources/css/backend.css',
                    'resources/js/backend.js',

                    // Bootstrap สำหรับ utilities ร่วม
                    'resources/js/bootstrap.js',
                ],
                refresh: true,
            }),
        ],

        build: {
            rollupOptions: {
                output: {
                    manualChunks: {
                        // ไม่ต้องแยก Alpine เพราะมาจาก Livewire
                        'vendor': ['axios']
                    }
                }
            }
        },

        resolve: {
            alias: {
                '@': '/resources',
                '@js': '/resources/js',
                '@css': '/resources/css',
            },
        },
        server: {
            cors: true,
            open: env.APP_URL,
        },
    };

});