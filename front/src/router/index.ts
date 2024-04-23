import { createWebHistory, createRouter } from 'vue-router';
import LoginPage from '../views/LoginPage.vue';

import HomePage from '../views/HomePage.vue'

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
]


const router = createRouter({
  history: createWebHistory(),
  routes,
})

export default router;