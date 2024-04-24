import { createWebHistory, createRouter, RouteRecordRaw } from 'vue-router';
import LoginPage from '../views/LoginPage.vue';
import RegisterPage from '../views/RegisterPage.vue';
import HomePage from '../views/HomePage.vue';
import VignificationComponent from "@/components/VignificationComponent.vue";
import AtelierComponent from "@/components/AtelierComponent.vue";

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
