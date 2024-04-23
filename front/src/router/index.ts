import { createWebHistory, createRouter } from 'vue-router';
import LoginPage from '../views/LoginPage.vue';

import HomePage from '../views/HomePage.vue'
import FooterCompoenent from "@/components/FooterCompoenent.vue";
import NavBar from "@/components/NavBar.vue";

const routes = [
  { path: '/', component: HomePage },
  {
    path: '/login',
    name: 'Login',
    component: LoginPage,
    meta: {
      layout: 'AppLayout'
    }
  },
  {
    path: '/footer',
    component: FooterCompoenent
  }
]


const router = createRouter({
  history: createWebHistory(),
  routes,
})

export default router;
