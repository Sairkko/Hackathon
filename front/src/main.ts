import { createApp } from 'vue'
import App from './App.vue'
import router from './router/index'
import PrimeVue from 'primevue/config'

import 'primevue/resources/themes/saga-blue/theme.css';
import 'primevue/resources/primevue.min.css';
import 'primeicons/primeicons.css';
import { pinia } from './store';
import Toast from 'primevue/toast';
import Card from 'primevue/card';
import Button from 'primevue/button';
import InputText from "primevue/inputtext";
import Dialog from 'primevue/dialog';
import Textarea from "primevue/textarea";


import './assets/tailwind.css';
import { library } from '@fortawesome/fontawesome-svg-core'
import {fas} from "@fortawesome/free-solid-svg-icons"
import { faXTwitter, faInstagram, faFacebookF, faTwitter } from '@fortawesome/free-brands-svg-icons';


import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';

library.add(fas, faXTwitter, faInstagram, faFacebookF, faTwitter)
createApp(App)
    .use(router)
    .use(PrimeVue)
    .use(pinia)
    .component('Toast',Toast)
    .component('Card',Card)
    .component('Button', Button)
    .component('InputText', InputText)
    .component('Dialog', Dialog)
    .component('Textarea', Textarea)
    .component("font-awesome-icon", FontAwesomeIcon)
    .mount('#app')
