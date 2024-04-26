import { createWebHistory, createRouter, RouteRecordRaw } from 'vue-router';
import LoginPage from '../views/LoginPage.vue';
import RegisterPage from '../views/RegisterPage.vue';
import HomePage from '../views/HomePage.vue';
import WorkshopDetailsPage from '../views/WorkshopDetailsPages.vue';
import MyReservation from "@/views/MyReservation.vue";
import VignificationComponent from "@/views/VignificationPage.vue";
import AtelierComponent from "@/components/AtelierComponent.vue";
import ProposPage from "@/views/ProposPage.vue";
import AdminAtelierPage from "@/views/AdminAtelierPage.vue";
import AtelierPage from "@/views/AtelierPage.vue";
import VignificationPage from "@/views/VignificationPage.vue";
import CalendrierPage from "@/views/CalendrierPage.vue"
import WineInventoryPage from "@/views/WineInventoryPage.vue";
import GestionProposPage from "@/views/GestionProposPage.vue";


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
      roles: ['ROLE_ADMIN', 'ROLE_USER'],
      layout: 'PageLayout'
    }
  },
  {
    path: '/detail',
    name: 'WorkshopDetailsPage',
    component: WorkshopDetailsPage,
  },
  {
    path: '/my-reservation',
    name: 'myReservation',
    component: MyReservation,
    meta: {
      roles: ['ROLE_USER'],
      layout: 'PageLayout'
    }
  },
  {
    path: '/vignification',
    name: 'vignification',
    component: VignificationPage,
    meta: {
      roles: ['ROLE_USER'],
      layout: 'PageLayout'
    }
  },
  {
    path: '/propos',
    name: 'propos',
    component: ProposPage,
    meta: {
      roles: ['ROLE_USER'],
      layout: 'PageLayout'
    }
  },
  {
    path: '/admin/propos',
    name: 'propos_Admin',
    component: GestionProposPage,
    meta: {
      roles: ['ROLE_USER'],
      layout: 'PageLayout'
    }
  },
  {
    path: '/atelier',
    name: 'atelier',
    component: AtelierPage,
    meta: {
      roles: ['ROLE_USER'],
      layout: 'PageLayout'
    }
  },
  {
    path: '/admin/atelier',
    name: 'atelier_Admin',
    component: AdminAtelierPage,
    meta: {
      roles: ['ROLE_ADMIN'],
      layout: 'PageLayout'
    }
  },
  {
    path: '/vignification',
    name: 'vignification',
    component: VignificationComponent,
    meta: {
      roles: ['ROLE_USER'],
      layout: 'PageLayout'
    }
  },

  {
    path: '/admin/calendrier',
    name: 'calendrierPage',
    component: CalendrierPage,
    meta: {
      roles: ['ROLE_ADMIN'],
      layout: 'PageLayout'
    }
  },
  {
    path: '/admin/inventaire',
    name: 'inventaire',
    component: WineInventoryPage,
    meta: {
      roles: ['ROLE_ADMIN'],
      layout: 'PageLayout'
    }
  }
];

const router = createRouter({
  history: createWebHistory(),
  routes,
})

export default router;
