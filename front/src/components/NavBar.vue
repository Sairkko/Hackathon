<script setup lang="ts">
import { computed, ref } from 'vue';
import router from '../router';
import { useUserStore } from '@/store/UserStrore';
import { useRoute } from 'vue-router';

const usersStore = useUserStore();
const user = computed(() => {
  return usersStore.getUser;
});

console.log(user.value?.role);

const isAtelierMenuOpen = ref(false);
const isAdminMenuOpen = ref (false);
const route = useRoute();

function toggleAdminMenu() {
  isAdminMenuOpen.value = !isAdminMenuOpen.value;
  if (isAdminMenuOpen.value) {
    isAtelierMenuOpen.value = false;  // Fermez l'autre menu
  }
}

function toggleAtelierMenu() {
  isAtelierMenuOpen.value = !isAtelierMenuOpen.value;
  if (isAtelierMenuOpen.value) {
    isAdminMenuOpen.value = false;  // Fermez l'autre menu
  }
}



function isActiveRoute(routeName: string) {
  return computed(() => route.name === routeName);
}

const deconnexion = () => {
  usersStore.setUser(null);
  localStorage.setItem("user", "");
  router.push({path: "/login"})
}

const isMenuOpen = ref(false);

function toggleMenu() {
  isMenuOpen.value = !isMenuOpen.value;
}
</script>


<template>
  <nav class="bg-white shadow-md">
    <div class="max-w-7xl mx-auto px-2 sm:px-4 lg:px-8">
      <div class="flex justify-between h-16">
        <div class="flex px-2 lg:px-0">
          <div class="flex-shrink-0 flex items-center">
            <a href="/">
              <img src="../assets/winApp.png" alt="Wine App Logo" class="h-16">
            </a>
          </div>
        </div>
        <div class="hidden md:flex" style="z-index: 1001;">
          <div v-if="user?.role === 'ROLE_ADMIN'" class="flex items-center space-x-4">
            <div class="relative" v-click-outside="{ open: isAdminMenuOpen, close: toggleAdminMenu }">
              <div>
                <button @click="toggleAdminMenu" type="button" class="inline-flex justify-center w-full rounded-md px-4 py-2 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none" id="menu-button" aria-expanded="true" aria-haspopup="true">
                  Administration de contenu
                </button>
              </div>

              <div v-show="isAdminMenuOpen" class="absolute right-0 mt-2 w-56 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 focus:outline-none" role="menu" aria-orientation="vertical" aria-labelledby="menu-button" tabindex="-1">
                <div class="py-1" role="none">
                  <router-link
                      :class="{'text-red hover:text-red-800': isActiveRoute('HomePage').value, 'text-gray-700 hover:text-gray-800': !isActiveRoute('HomePage').value}"
                      :to="{ name: 'HomePage' }"
                      class="block px-4 py-2 text-sm"
                      role="menuitem"
                      tabindex="-1"
                      id="menu-item-0"
                  >
                    Accueil
                  </router-link>
                  <router-link
                      :class="{'text-red hover:text-red-800': isActiveRoute('HomePage').value, 'text-gray-700 hover:text-gray-800': !isActiveRoute('HomePage').value}"
                      :to="{ name: 'HomePage' }"
                      class="block px-4 py-2 text-sm"
                      role="menuitem"
                      tabindex="-1"
                      id="menu-item-0"
                  >
                    Procédé de vinification
                  </router-link>
                  <router-link
                      :class="{'text-red hover:text-red-800': isActiveRoute('HomePage').value, 'text-gray-700 hover:text-gray-800': !isActiveRoute('HomePage').value}"
                      :to="{ name: 'HomePage' }"
                      class="block px-4 py-2 text-sm"
                      role="menuitem"
                      tabindex="-1"
                      id="menu-item-0"
                  >
                    Détail d'un atelier
                  </router-link>
                  <router-link
                      :class="{'text-red hover:text-red-800': isActiveRoute('propos_Admin').value, 'text-gray-700 hover:text-gray-800': !isActiveRoute('propos_Admin').value}"
                      :to="{ name: 'propos_Admin' }"
                      class="block px-4 py-2 text-sm"
                      role="menuitem"
                      tabindex="-1"
                      id="menu-item-0"
                  >
                    A propos
                  </router-link>
                </div>
              </div>
            </div>
            <div class="relative" v-click-outside="{ open: isAtelierMenuOpen, close: toggleAtelierMenu }">
              <div>
                <button @click="toggleAtelierMenu" type="button" class="inline-flex justify-center w-full rounded-md px-4 py-2 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none" id="menu-button" aria-expanded="true" aria-haspopup="true">
                  Gestion
                </button>
              </div>

              <div v-show="isAtelierMenuOpen" class="origin-top-middle absolute right-0 mt-2 w-56 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 focus:outline-none" role="menu" aria-orientation="vertical" aria-labelledby="menu-button" tabindex="-1">
                <div class="py-1" role="none">
                  <router-link
                      :class="{'text-red hover:text-red-800': isActiveRoute('atelier_Admin').value, 'text-gray-700 hover:text-gray-800': !isActiveRoute('atelier_Admin').value}"
                      :to="{ name: 'atelier_Admin' }"
                      class="block px-4 py-2 text-sm"
                      role="menuitem"
                      tabindex="-1"
                      id="menu-item-0"
                  >
                    Gestion des ateliers
                  </router-link>
                  <router-link :to="{name: 'HomePage'}" class="text-gray-700 block px-4 py-2 text-sm hover:bg-gray-100" role="menuitem" tabindex="-1" id="menu-item-0">Gestion des inscriptions</router-link>
                  <router-link
                      :class="{'text-red hover:text-red-800': isActiveRoute('inventaire').value, 'text-gray-700 hover:text-gray-800': !isActiveRoute('inventaire').value}"
                      :to="{ name: 'inventaire' }"
                      class="block px-4 py-2 text-sm"
                      role="menuitem"
                      tabindex="-1"
                      id="menu-item-0"
                  >
                    Inventaire des vins
                  </router-link>
                </div>
              </div>
            </div>
            <router-link :to="{name: 'calendrierPage'}">
              <span class="text-gray-700 hover:text-gray-800 px-3 py-2 rounded-md text-sm font-medium">Calendrier des réservations</span>
            </router-link>
          </div>
          <div v-else class="flex items-center space-x-4">
            <router-link :to="{name: 'vignification'}">
              <span class="text-gray-700 hover:text-gray-800 px-3 py-2 rounded-md text-sm font-medium">Procédé de vinification</span>
            </router-link>
            <router-link :to="{name: 'atelier'}">
              <span class="text-gray-700 hover:text-gray-800 px-3 py-2 rounded-md text-sm font-medium">Les ateliers</span>
            </router-link>
            <router-link :to="{name: 'myReservation'}">
              <span class="text-gray-700 hover:text-gray-800 px-3 py-2 rounded-md text-sm font-medium">Mes réservations</span>
            </router-link>
            <router-link :to="{name: 'propos'}">
              <span class="text-gray-700 hover:text-gray-800 px-3 py-2 rounded-md text-sm font-medium">À propos</span>
            </router-link>
          </div>
        </div>
        <div class="flex items-center md:hidden">
          <!-- Bouton hamburger -->
          <button @click="toggleMenu" class="hamburger" aria-label="Menu">
            ☰
          </button>
          <div v-if="user?.role === 'ROLE_ADMIN'" class="mobile-menu flex-col space-y-1 bg-white shadow-md" v-show="isMenuOpen">
            <div class="relative">
              <div>
                <button @click="toggleAdminMenu" type="button" class="inline-flex justify-center w-full rounded-md px-4 py-2 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none" id="menu-button" aria-expanded="true" aria-haspopup="true">
                  Administration de contenu
                </button>
              </div>

              <div style="z-index: 1000;" v-show="isAdminMenuOpen" class="origin-top-right absolute right-0 mt-2 w-56 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 focus:outline-none" role="menu" aria-orientation="vertical" aria-labelledby="menu-button" tabindex="-1">
                <div class="py-1" role="none">
                  <router-link
                      :class="{'text-red hover:text-red-800': isActiveRoute('HomePage').value, 'text-gray-700 hover:text-gray-800': !isActiveRoute('HomePage').value}"
                      :to="{ name: 'HomePage' }"
                      class="block px-4 py-2 text-sm"
                      role="menuitem"
                      tabindex="-1"
                      id="menu-item-0"
                  >
                    Accueil
                  </router-link>
                  <router-link
                      :class="{'text-red hover:text-red-800': isActiveRoute('HomePage').value, 'text-gray-700 hover:text-gray-800': !isActiveRoute('HomePage').value}"
                      :to="{ name: 'HomePage' }"
                      class="block px-4 py-2 text-sm"
                      role="menuitem"
                      tabindex="-1"
                      id="menu-item-0"
                  >
                    Procédé de vinification
                  </router-link>
                  <router-link
                      :class="{'text-red hover:text-red-800': isActiveRoute('HomePage').value, 'text-gray-700 hover:text-gray-800': !isActiveRoute('HomePage').value}"
                      :to="{ name: 'HomePage' }"
                      class="block px-4 py-2 text-sm"
                      role="menuitem"
                      tabindex="-1"
                      id="menu-item-0"
                  >
                    Détail d'un atelier
                  </router-link>
                  <router-link
                      :class="{'text-red hover:text-red-800': isActiveRoute('propos_Admin').value, 'text-gray-700 hover:text-gray-800': !isActiveRoute('propos_Admin').value}"
                      :to="{ name: 'propos_Admin' }"
                      class="block px-4 py-2 text-sm"
                      role="menuitem"
                      tabindex="-1"
                      id="menu-item-0"
                  >
                    A propos
                  </router-link>
                </div>
              </div>
            </div>
            <div class="relative">
              <div>
                <button @click="toggleAtelierMenu" type="button" class="inline-flex justify-center w-full rounded-md px-4 py-2 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none" id="menu-button" aria-expanded="true" aria-haspopup="true">
                  Gestion
                </button>
              </div>

              <div v-show="isAtelierMenuOpen" class="origin-top-right absolute right-0 mt-2 w-56 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 focus:outline-none" role="menu" aria-orientation="vertical" aria-labelledby="menu-button" tabindex="-1">
                <div class="py-1" role="none">
                  <router-link
                      :class="{'text-red hover:text-red-800': isActiveRoute('atelier_Admin').value, 'text-gray-700 hover:text-gray-800': !isActiveRoute('atelier_Admin').value}"
                      :to="{ name: 'atelier_Admin' }"
                      class="block px-4 py-2 text-sm"
                      role="menuitem"
                      tabindex="-1"
                      id="menu-item-0"
                  >
                    Gestion des ateliers
                  </router-link>
                  <router-link :to="{name: 'HomePage'}" class="text-gray-700 block px-4 py-2 text-sm hover:bg-gray-100" role="menuitem" tabindex="-1" id="menu-item-0">Gestion des inscriptions</router-link>
                  <router-link
                      :class="{'text-red hover:text-red-800': isActiveRoute('inventaire').value, 'text-gray-700 hover:text-gray-800': !isActiveRoute('inventaire').value}"
                      :to="{ name: 'inventaire' }"
                      class="block px-4 py-2 text-sm"
                      role="menuitem"
                      tabindex="-1"
                      id="menu-item-0"
                  >
                    Inventaire des vins
                  </router-link>
                </div>
              </div>
            </div>
            <router-link :to="{name: 'calendrierPage'}">
              <span class="text-gray-700 hover:text-gray-800 px-3 py-2 rounded-md text-sm font-medium">Calendrier des réservations</span>
            </router-link>
          </div>
          <!-- Menu mobile -->
          <div v-else class="mobile-menu flex-col space-y-1 bg-white shadow-md" v-show="isMenuOpen">
            <router-link :to="{name: 'vignification'}">
              <span class="text-gray-700 hover:text-gray-800 px-3 py-2 rounded-md text-sm font-medium">Procédé de vinification</span>
            </router-link>
            <router-link :to="{name: 'atelier'}">
              <span class="text-gray-700 hover:text-gray-800 px-3 py-2 rounded-md text-sm font-medium">Les ateliers</span>
            </router-link>
            <router-link :to="{name: 'myReservation'}">
              <span class="text-gray-700 hover:text-gray-800 px-3 py-2 rounded-md text-sm font-medium">Mes réservations</span>
            </router-link>
            <router-link :to="{name: 'propos'}">
              <span class="text-gray-700 hover:text-gray-800 px-3 py-2 rounded-md text-sm font-medium">À propos</span>
            </router-link>
          </div>
        </div>
        <div class="flex items-center">
          <div v-if="user" class="text-sm text-red">
            <font-awesome-icon icon="user" class="mr-1" /><span>{{user.firstName}}</span>
            <font-awesome-icon @click="deconnexion" class="ml-4 text-dark-red hover:text-red" icon="sign-out-alt" />
            <!-- <button @click="deconnexion" class="hidden md:block text-white bg-red hover:bg-hover-red px-5 py-2 rounded-md text-sm font-medium">Déconnexion</button> -->
          </div>
          <router-link v-else :to="{name: 'LoginPage'}">
            <span class="text-white bg-red hover:bg-hover-red px-5 py-2 rounded-md text-sm font-medium">Connexion</span>
          </router-link>
        </div>
      </div>
    </div>
  </nav>
</template>

<style scoped>
.hamburger {
  display: block;
  background: none;
  border: none;
  font-size: 24px;
}

.mobile-menu {
  display: flex;
  flex-direction: column;
  padding: 10px;
  margin-top: 170px;
  z-index: 1000;
}

.mobile-menu a {
  text-align: center;
}

.origin-top-middle {
  position: absolute;
  left: 50%;
  transform: translateX(-50%);
}
</style>

