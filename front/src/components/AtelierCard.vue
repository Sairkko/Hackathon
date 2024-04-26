<template>
<div class="flex flex-row overflow-hidden rounded-lg shadow-md mb-8 mx-4 md:mx-auto max-w-4xl">
  <img v-if="props.thematique === 'Exploration Céleste'"
       class="w-full md:w-2/6 object-cover"
       src="../assets/ExploType.png"
       alt="Wine tasting experience"
  >
  <img v-else
       class="w-full md:w-2/6 object-cover"
       src="../assets/atelier.png"
       alt="Wine tasting experience"
  >
  <div class="w-full md:w-3/5 lg:w-4/5 p-4 bg-white flex flex-row justify-between gap-8">
    <div class="flex flex-col w-full">
      <h1 class="text-xl bg-white font-bold text-start text-red mb-2">{{ props.nom }}</h1>
      <p class="text-dark-red bg-white text-justify mb-4">{{ props.description }}</p>
    </div>
    <div class="flex flex-col justify-center gap-4">
      <router-link :to="{ name: 'WorkshopDetailsPage', params: { id: props.id } }" style="width: 160px" class="border text-white border-red bg-red py-2 px-4 rounded hover:bg-red-700 transition duration-300">
        Détails
      </router-link>
    </div>

  </div>
</div>
</template>

<script setup lang="ts">
import Atelier from '../models/Atelier';

// eslint-disable-next-line no-unused-vars,no-undef
const props = defineProps<{
  modelValue: Atelier,
  id?: String,
  nom?: String,
  description?: String,
  thematique?: String
}>();
// eslint-disable-next-line no-unused-vars,no-undef
const emit = defineEmits<{
  // eslint-disable-next-line no-unused-vars
  (event: 'update:modelValue', value: Atelier): void
}>();
</script>

