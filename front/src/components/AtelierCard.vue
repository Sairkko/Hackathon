<template>
<div class="flex flex-row overflow-hidden rounded-lg shadow-md mb-8 mx-4 md:mx-auto max-w-4xl">
  <img class="w-full md:w-2/5 lg:w-1/5 object-cover" src="../assets/atelier.png" alt="Wine tasting experience">
  <div class="w-full md:w-3/5 lg:w-4/5 p-4 bg-white flex flex-row justify-between gap-4">
    <div class="flex flex-col w-full">
      <h1 class="text-xl bg-white font-bold text-start text-red mb-2">{{ props.nom }}</h1>
      <p class="text-dark-red bg-white text-start mb-4">{{ props.description }}</p>
    </div>
    <div class="flex flex-col justify-center gap-4">
      <button style="width: 160px" class="border text-white border-red bg-red py-2 px-4 rounded hover:bg-red-700 transition duration-300">Détails</button>
    </div>
  </div>
</div>
</template>

<script setup lang="ts">
import Atelier from '../models/Atelier';

// eslint-disable-next-line no-unused-vars,no-undef
const props = defineProps<{
  modelValue: Atelier,
  nom?: String,
  description?: String
}>();
// eslint-disable-next-line no-unused-vars,no-undef
const emit = defineEmits<{
  // eslint-disable-next-line no-unused-vars
  (event: 'update:modelValue', value: Atelier): void
}>();
</script>

