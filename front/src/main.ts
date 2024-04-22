import { createApp } from 'vue'
import App from './App.vue'
import router from './router/index'
import PrimeVue from 'primevue/config';

import 'primevue/resources/themes/saga-blue/theme.css';
import 'primevue/resources/primevue.min.css';
import 'primeicons/primeicons.css';
import { pinia } from './store';
import Toast from 'primevue/toast';


createApp(App)
    .use(router)
    .use(PrimeVue)
    .use(pinia)
    .component('Toast',Toast)
    .mount('#app')
