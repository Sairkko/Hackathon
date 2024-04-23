import { createWebHistory, createRouter, RouteRecordRaw } from 'vue-router';
import LoginPage from '../views/LoginPage.vue';
import RegisterPage from '../views/RegisterPage.vue';
import HomePage from '../views/HomePage.vue';

import HomePage from '../views/HomePage.vue'
import FooterCompoenent from "@/components/FooterCompoenent.vue";

const routes = [
  { path: '/', component: HomePage },
const routes: RouteRecordRaw[] = [
  {
    path: '/',
    redirect: '/home',
  },
  {
    path: '/login',
    name: 'LoginPage',
    component: LoginPage,
    meta: {
      layout: 'BaseLayout'
    }
  },
  {
    path: '/footer',
    component: FooterCompoenent
  }
    path: '/register',
    name: 'RegisterPage',
    component: RegisterPage,
    meta: {
      layout: 'BaseLayout'
    }
  },
  {
    path: '/home',
    name: 'HomePage',
    component: HomePage,
    meta: {
      layout: 'PageLayout'
    }
  }
];

const router = createRouter({
  history: createWebHistory(),
  routes,
})

export default router;
