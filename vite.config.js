import inject from '@rollup/plugin-inject'
import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import mjml from 'vite-plugin-mjml'

export default defineConfig({
    server: {
        host: "0.0.0.0",
        hmr: {
            host: 'localhost'
        }
    },
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/scss/theme.scss', 'resources/js/app.js', 'resources/js/main.js'],
            refresh: true,
        }),
        mjml({
			input: 'resources/views/emails/mjml',
			output: 'resources/views/emails',
			extension: '.blade.php',
		}),
        inject({
            htmx: 'htmx.org'
        }),
    ],
});
