<script setup lang="ts">
import { onMounted, ref } from 'vue';
import Atelier from '../models/Atelier';
import UserApi from '@/api/UserApi';
import AtelierCard from '@/components/AtelierCard.vue';

const ateliers = ref<Atelier[]>([]);

onMounted(async () => {
  try {
    const response = await UserApi.atelier();
    ateliers.value = response.data.data;
  } catch (error) {
    console.error('There has been a problem with your operation:', error);
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
    <div v-for="(atelier) in ateliers" :key="atelier.id">
      <AtelierCard :nom="atelier.nom" :description="atelier.description"/>
    </div>
  </div>
</template>
