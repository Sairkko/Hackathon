<script setup lang="ts">
import { onMounted, ref } from 'vue';

const ateliers = ref([]);

onMounted(async () => {
  try {
    const response = await fetch('https://127.0.0.1:8000/atelier/all');
    if (!response.ok) {
      throw new Error('Network response was not ok ' + response.statusText);
    }
    const data = await response.json();
    ateliers.value = data.data;
  } catch (error) {
    console.error('There has been a problem with your fetch operation:', error);
  }
});
</script>

<template>
  <div class="bg-white text-gray-800">
    <section class="px-4 py-16">
      <h1 class="text-3xl font-bold text-center text-red mb-10">Exploration Terrestre : <span class="text-dark-red">Plongée dans les Saveurs Vinicoles</span></h1>
      <p class="mt-4 mx-16 text-justify font-semibold">
        Découvrez nos différents ateliers, dédiés à l'exploration des saveurs et des arômes à travers des dégustations de vin uniques
      </p>
    </section>
    <div v-for="atelier in ateliers" :key="atelier.id" class="flex flex-row overflow-hidden rounded-lg shadow-md mb-8 mx-4 md:mx-auto max-w-4xl">
      <img class="w-full md:w-2/5 lg:w-1/5 object-cover" src="../assets/atelier.png" alt="Wine tasting experience">
      <div class="w-full md:w-3/5 lg:w-4/5 p-4 bg-white flex flex-row justify-between gap-4">
        <div class="flex flex-col">
          <h3 class="text-xl font-bold text-start text-red mb-2">{{ atelier.nom }}</h3>
          <p class="text-dark-red text-start mb-4">
            {{ atelier.description }}
          </p>
        </div>
        <div class="flex flex-col justify-center gap-4">
          <button style="width: 160px" class="border text-white border-red bg-red py-2 px-4 rounded hover:bg-red-700 transition duration-300">Détails</button>
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped>
</style>
