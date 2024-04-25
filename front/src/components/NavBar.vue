<script setup lang="ts">
import { computed, ref } from 'vue';
import router from '../router';
import { useUserStore } from '../store/UserStrore';
const usersStore = useUserStore();
const user = computed(() => {
  return usersStore.getUser;
});

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
        <div class="hidden md:flex">
          <div class="flex items-center space-x-4">
            <router-link :to="{name: 'VignificationComponent'}">
              <span class="text-gray-700 hover:text-gray-800 px-3 py-2 rounded-md text-sm font-medium">Procédé de vinification</span>
            </router-link>
            <router-link :to="{name: 'atelier'}">
              <span class="text-gray-700 hover:text-gray-800 px-3 py-2 rounded-md text-sm font-medium">Les ateliers</span>
            </router-link>
            <router-link :to="{name: 'MyReservation'}">
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
          <!-- Menu mobile -->
          <div class="mobile-menu flex-col space-y-1 bg-white shadow-md" v-show="isMenuOpen">
            <router-link :to="{name: 'VignificationComponent'}">
              <span class="text-gray-700 hover:text-gray-800 px-3 py-2 rounded-md text-sm font-medium">Procédé de vinification</span>
            </router-link>
            <router-link :to="{name: 'atelier'}">
              <span class="text-gray-700 hover:text-gray-800 px-3 py-2 rounded-md text-sm font-medium">Les ateliers</span>
            </router-link>
            <router-link :to="{name: 'MyReservation'}">
              <span class="text-gray-700 hover:text-gray-800 px-3 py-2 rounded-md text-sm font-medium">Mes réservations</span>
            </router-link>
            <router-link :to="{name: 'propos'}">
              <span class="text-gray-700 hover:text-gray-800 px-3 py-2 rounded-md text-sm font-medium">À propos</span>
            </router-link>
          </div>
        </div>
        <div class="flex items-center">
          <div v-if="user" class="text-sm text-red">
            <font-awesome-icon icon="user" class="mr-1" /><span>{{user.prenom}}</span>
            <font-awesome-icon @click="deconnexion" class="md:hidden ml-4 text-dark-red hover:text-red" icon="sign-out-alt" />
            <button @click="deconnexion" class="hidden md:block text-white bg-red hover:bg-hover-red px-5 py-2 rounded-md text-sm font-medium">Déconnexion</button>
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
</style>

