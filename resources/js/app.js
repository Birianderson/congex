import './bootstrap';
import * as bootstrap from 'bootstrap';
import '../css/app.css';
import { createApp, defineAsyncComponent } from 'vue/dist/vue.esm-bundler';
import mitt from 'mitt';
const app = createApp({});
import Toast from "vue-toastification";
import VueTheMask from 'vue-the-mask'
import DataTable from 'datatables.net-vue3';
import VueApexCharts from "vue3-apexcharts";
const components = import.meta.glob('./components/**/*.vue', {eager: true});

Object.entries(components).forEach(([path, definition]) => {
    const componentName = path.split('/').pop().split('.')[0];
    if(componentName.indexOf('__') === -1) {
        app.component(componentName, definition.default)
    }
})
app.use(VueApexCharts);
app.use(VueTheMask);
app.use(Toast, {});
app.provide('events', mitt());
app.mount('#app');


