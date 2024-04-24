<script setup lang="ts">
</script>

<template>
  <footer class="bg-red text-white p-4 md:p-6 inset-x-0 bottom-0">
    <div class="max-w-6xl mx-20 flex flex-col md:flex-row justify-between items-center">
      <div class="flex justify-center md:justify-start items-center mb-4 space-x-2">
        <div class="bg-white p-2 rounded my-4">
          <img src="@/assets/winApp.png" alt="Wine App logo" class="lg:h-20 h-14"/>
        </div>
      </div>
      <div class="flex flex-col font-bold">
        <div class="flex md:flex-wrap md:px-4 md:gap-6  justify-around mb-4">
          <a href="#" class="block md:inline hover:text-gray-200 mx-2">Procédé de vinification</a>
          <a href="#" class="block md:inline hover:text-gray-200 mx-2">Les ateliers</a>
        </div>
        <div class="flex md:flex-wrap md:px-4 md:gap-10 gap-2 justify-around mb-4">
          <a href="#" class="block md:inline hover:text-gray-200 mx-2">Mes réservations</a>
          <a href="#" class="block lg:inline hover:text-gray-200 mx-2">À propos</a>
        </div>
      </div>
      <div class="flex justify-center md:justify-end items-center space-x-4">
        <a href="#" aria-label="Facebook" class="hover:text-gray-200">
          <font-awesome-icon :icon="['fab', 'facebook-f']" />
        </a>
        <a href="#" aria-label="Twitter" class="hover:text-gray-200">
          <font-awesome-icon :icon="['fab', 'x-twitter']" />
        </a>
        <a href="#" aria-label="Instagram" class="hover:text-gray-200 bg-dark">
          <font-awesome-icon :icon="['fab', 'instagram']" />
        </a>
      </div>
    </div>
    <div class="border-t-2 border-white my-2 mx-12"></div>
    <div class="flex flex-col md:flex-row font-bold justify-between items-center mx-12">
      <span class="mb-2 md:mb-0">&copy; 2024 by les loulou team</span>
      <div class="flex justify-center md:justify-end items-center space-x-4">
        <a href="#" class="hover:text-gray-200">Termes et condition</a>
        <a href="#" class="hover:text-gray-200">Politique de confidentialité</a>
      </div>
    </div>
  </footer>
</template>

<style scoped>
</style>
