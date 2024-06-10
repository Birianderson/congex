import './bootstrap';
import * as bootstrap from 'bootstrap';
import '../css/app.css';
import { createApp, defineAsyncComponent } from 'vue/dist/vue.esm-bundler';

const app = createApp({});

const components = import.meta.glob('./components/**/*.vue', {eager: true});

Object.entries(components).forEach(([path, definition]) => {
    const componentName = path.split('/').pop().split('.')[0];
    if(componentName.indexOf('__') === -1) {
        app.component(componentName, definition.default)
    }
})

app.mount('#app');
