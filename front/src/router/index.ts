import { createWebHistory, createRouter, RouteRecordRaw } from 'vue-router';
import LoginPage from '../views/LoginPage.vue';
import RegisterPage from '../views/RegisterPage.vue';
import HomePage from '../views/HomePage.vue';
import MyReservation from "@/views/MyReservation.vue";
import AtelierComponent from "@/components/AtelierComponent.vue";
import ProposComponent from "@/components/ProposComponent.vue";
import ProposPage from "@/views/ProposPage.vue";
import VignificationPage from "@/views/VignificationPage.vue";

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
  },
  {
    path: '/my-reservation',
    name: 'MyReservation',
    component: MyReservation,
    meta: {
      layout: 'PageLayout'
    }
  },
  {
    path: '/vignification',
    name: 'vignification',
    component: VignificationPage,
    meta: {
      layout: 'PageLayout'
    }
  },
  {
    path: '/propos',
    name: 'propos',
    component: ProposPage,
    meta: {
      layout: 'PageLayout'
    }
  },
  {
    path: '/atelier',
    name: 'atelier',
    component: AtelierComponent,
    meta: {
      layout: 'PageLayout'
    }
  },
];

const router = createRouter({
  history: createWebHistory(),
  routes,
})

export default router;
