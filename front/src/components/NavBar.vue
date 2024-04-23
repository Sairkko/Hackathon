<script setup lang="ts">
import { ref } from 'vue';

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
            <img src="../assets/winApp.png" alt="Wine App Logo" class="h-16">
          </div>
        </div>
        <div class="hidden md:flex">
          <div class="flex items-center space-x-4">
            <a href="#" class="text-gray-700 hover:text-gray-800 px-3 py-2 rounded-md text-sm font-medium">Accueil</a>
            <a href="#" class="text-gray-700 hover:text-gray-800 px-3 py-2 rounded-md text-sm font-medium">Procédé de vinification</a>
            <a href="#" class="text-gray-700 hover:text-gray-800 px-3 py-2 rounded-md text-sm font-medium">Les ateliers</a>
            <a href="#" class="text-gray-700 hover:text-gray-800 px-3 py-2 rounded-md text-sm font-medium">Mes réservations</a>
            <a href="#" class="text-gray-700 hover:text-gray-800 px-3 py-2 rounded-md text-sm font-medium">À propos</a>
          </div>
        </div>
        <div class="flex items-center md:hidden">
          <!-- Bouton hamburger -->
          <button @click="toggleMenu" class="hamburger" aria-label="Menu">
            ☰
          </button>
          <!-- Menu mobile -->
          <div class="mobile-menu flex-col space-y-1 bg-white shadow-md" v-show="isMenuOpen">
            <a href="#" class="text-gray-700 hover:text-gray-800 px-3 py-2 rounded-md text-sm font-medium">Accueil</a>
            <a href="#" class="text-gray-700 hover:text-gray-800 px-3 py-2 rounded-md text-sm font-medium">Procédé de vinification</a>
            <a href="#" class="text-gray-700 hover:text-gray-800 px-3 py-2 rounded-md text-sm font-medium">Les ateliers</a>
            <a href="#" class="text-gray-700 hover:text-gray-800 px-3 py-2 rounded-md text-sm font-medium">Mes réservations</a>
            <a href="#" class="text-gray-700 hover:text-gray-800 px-3 py-2 rounded-md text-sm font-medium">À propos</a>
          </div>
        </div>
        <div class="flex items-center">
          <a href="#" class="text-white bg-red hover:bg-hover-red px-5 py-2 rounded-md text-sm font-medium">Connexion</a>
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
}

.mobile-menu a {
  text-align: center;
}
</style>

