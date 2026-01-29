import { createApp, h } from 'vue';
import { createInertiaApp, Link, Head } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import 'startup-ui/dist/index.css';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
import './font-awesome.js';

createInertiaApp({
    resolve: name => resolvePageComponent(
        `../views/pages/${name}.vue`,
        import.meta.glob('../views/pages/**/*.vue')
    ),
    setup({ el, App, props, plugin }) {
        createApp({ render: () => h(App, props) })
            .use(plugin)
            .component('FontAwesomeIcon', FontAwesomeIcon)
            .component('Link', Link)
            .component('Head', Head)
            .mount(el);
    },
});

