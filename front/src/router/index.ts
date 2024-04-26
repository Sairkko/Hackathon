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
import ResetPasswordPage from "@/views/ResetPasswordPage.vue";
import NewPasswordPage from "@/views/NewPasswordPage.vue";


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
    path: '/resetPassword',
    name: 'ResetPasswordPage',
    component: ResetPasswordPage,
    meta: {
      layout: 'BaseLayout'
    }
  },
  {
    path: '/newPassword',
    name: 'NewPasswordPage',
    component: NewPasswordPage,
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
    path: '/atelier/:id',
    name: 'WorkshopDetailsPage',
    props: true,
    component: WorkshopDetailsPage,
  },
  {
    path: '/my-reservation',
    name: 'myReservation',
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
    path: '/admin/propos',
    name: 'propos_Admin',
    component: GestionProposPage,
    meta: {
      layout: 'PageLayout'
    }
  },
  {
    path: '/atelier',
    name: 'atelier',
    component: AtelierPage,
    meta: {
      layout: 'PageLayout'
    }
  },
  {
    path: '/admin/atelier',
    name: 'atelier_Admin',
    component: AdminAtelierPage,
    meta: {
      layout: 'PageLayout'
    }
  },
  {
    path: '/vignification',
    name: 'vignification',
    component: VignificationComponent,
    meta: {
      layout: 'PageLayout'
    }
  },

  {
    path: '/admin/calendrier',
    name: 'calendrierPage',
    component: CalendrierPage,
    meta: {
      layout: 'PageLayout'
    }
  },
  {
    path: '/admin/inventaire',
    name: 'inventaire',
    component: WineInventoryPage,
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
