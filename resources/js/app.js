import {createApp, h} from 'vue'
import {createInertiaApp} from '@inertiajs/inertia-vue3'
import {InertiaProgress} from '@inertiajs/progress'
import VueSweetalert2 from 'vue-sweetalert2';
import 'sweetalert2/dist/sweetalert2.min.css';

createInertiaApp({
    resolve: name => require(`./Pages/${name}`), setup({el, App, props, plugin}) {
        createApp({render: () => h(App, props)})
            .use(plugin)
            .use(VueSweetalert2)
            .mount(el)
    },
})


InertiaProgress.init({
    delay: 250, color: '#dd223e', includeCSS: true, showSpinner: false,
})
