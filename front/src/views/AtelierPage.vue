<script setup lang="ts">
import { onMounted, ref, reactive, computed } from 'vue';
import Atelier from '../models/Atelier';
import AtelierApi from '@/api/AtelierApi';
import AtelierCard from '@/components/AtelierCard.vue';
import Dropdown from "primevue/dropdown";

const isLoading = ref(false);
const ateliers = ref<Atelier[]>([]);
const ateliersParThematique = reactive<{ [key: string]: Atelier[] }>({});
const selectedThematique = ref(null);
interface DescriptionMap {
  [key: string]: string;
}

const thematiqueOptions = computed(() => {
  return [{ label: 'Tous', value: null }, ...Object.keys(ateliersParThematique).map(key => ({ label: key, value: key }))];
});

const filteredAteliers = computed(() => {
  if (selectedThematique.value) {
    return ateliersParThematique[selectedThematique.value];
  }
  return ateliers.value; // Retourne tous les ateliers si aucune thématique n'est sélectionnée
});

const descriptionParThematique: DescriptionMap = {
  'Exploration Terrestre': 'Explorez les secrets des vins régionaux, des blancs subtils aux rouges profonds, en passant par les spécialités uniques.',
  'Exploration Céleste': 'Sous l\'égide du Vin et des Étoiles, plongez dans un Voyage Vinicole au Cœur du Cosmos.'
};

onMounted(async () => {
  try {
    isLoading.value = true;
    const response = await AtelierApi.atelier();
    ateliers.value = response.data.data;
    organiserAteliersParThematique();
    setTimeout(() => {
      isLoading.value = false;
    }, 500);
  } catch (error) {
    console.error('There has been a problem with your operation:', error);
  }
});

function organiserAteliersParThematique() {
  ateliers.value.forEach((atelier) => {
    const thematique = atelier.thematique || 'Autres'; // Assurez-vous que chaque atelier a une propriété 'thematique'
    if (!ateliersParThematique[thematique]) {
      ateliersParThematique[thematique] = [];
    }
    ateliersParThematique[thematique].push(atelier);
  });
}
</script>

<template>
  <div class="text-gray-800">
    <div v-if="isLoading" class="loading-overlay text-6xl">
      <font-awesome-icon :icon="'wine-bottle'" class="loading-icon"/>
    </div>
    <div v-else>
    <div class="flex justify-center md:justify-end my-4 mx-20">
      <Dropdown v-model="selectedThematique" :options="thematiqueOptions" optionLabel="label" optionValue="value" placeholder="Sélectionnez une thématique" class="p-dropdown border text-dark-red" style="width: 300px" />
    </div>
    <section v-for="atelier in filteredAteliers" :key="atelier.id" class="px-4 py-4">
      <h1 class="text-3xl font-bold text-center text-dark-red mb-10">{{ atelier.thematique }}</h1>
      <p class="my-4 mx-16 text-center font-semibold">
        {{ descriptionParThematique[atelier.thematique!] }}
      </p>
      <AtelierCard :nom="atelier.nom" :description="atelier.description" :thematique="atelier.thematique"/>
    </section>
    </div>
  </div>
</template>

<style scoped>
@keyframes spin {
  from { transform: rotate(0deg); }
  to { transform: rotate(360deg); }
}

.loading-icon {
  animation: spin 1s linear infinite;
}

.loading-overlay {
  width: 100%;
  height: 100vh;
  background: rgba(0, 0, 0, 0.06);
  display: flex;
  justify-content: center;
  align-items: center;
}
</style>



