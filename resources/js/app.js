import '../css/app.css';
import './bootstrap';

import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { createApp, h } from 'vue';
import { createPinia } from 'pinia';

import { ZiggyVue } from 'ziggy-js';


import toastr from 'toastr';
import Swal from 'sweetalert2';



window.toastr = toastr;
window.Swal = Swal;

// Configure toastr
toastr.options = {
    "closeButton": true,
    "progressBar": true,
    "positionClass": "toast-top-right",
};

const appName = import.meta.env.VITE_APP_NAME || 'Laptech';

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) =>
        resolvePageComponent(
            `./Pages/${name}.vue`,
            import.meta.glob('./Pages/**/*.vue'),
        ),
    setup({ el, App, props, plugin }) {
        const pinia = createPinia();
        const app = createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(pinia)
            .use(ZiggyVue);


        // Make toastr and Swal available in Vue components
        app.config.globalProperties.$toastr = toastr;
        app.config.globalProperties.$swal = Swal;

        return app.mount(el);
    },
    progress: {
        color: '#008080',
    },
});

