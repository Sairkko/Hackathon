import { createApp } from 'vue'
import App from './App.vue'
import router from './router/index'
import PrimeVue from 'primevue/config'
import './assets/tailwind.css';

import 'primevue/resources/themes/aura-light-pink/theme.css';
import 'primevue/resources/primevue.min.css';
import 'primeicons/primeicons.css';
import { pinia } from './store';
import Toast from 'primevue/toast';
import ConfirmDialog from 'primevue/confirmdialog';
import Card from 'primevue/card';
import Button from 'primevue/button';
import InputText from "primevue/inputtext";
import Dialog from 'primevue/dialog';
import Textarea from "primevue/textarea";
import Dropdown from "primevue/dropdown";
import InputGroup from "primevue/inputgroup";
import ToastService from 'primevue/toastservice';
import ConfirmService from 'primevue/confirmationservice';

import { library } from '@fortawesome/fontawesome-svg-core'
import {fas} from "@fortawesome/free-solid-svg-icons"
import { faXTwitter, faInstagram, faFacebookF, faTwitter } from '@fortawesome/free-brands-svg-icons';

import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';

library.add(fas, faXTwitter, faInstagram, faFacebookF, faTwitter)
createApp(App)
    .use(router)
    .use(PrimeVue)
    .use(pinia)
    .use(ToastService)
    .use(ConfirmService)
    .component('Toast',Toast)
    .component('ConfirmDialog',ConfirmDialog)
    .component('Card',Card)
    .component('Button', Button)
    .component('InputText', InputText)
    .component('Dialog', Dialog)
    .component('Textarea', Textarea)
    .component('Dropdown', Dropdown)
    .component('InputGroup', InputGroup)
    .component("font-awesome-icon", FontAwesomeIcon)
    .mount('#app')
