<script setup lang="ts">
import { onMounted, ref, reactive } from 'vue';
import Atelier from '../models/Atelier';
import AtelierApi from '@/api/AtelierApi';
import AtelierCard from '@/components/AtelierCard.vue';

const isLoading = ref(false);
const ateliers = ref<Atelier[]>([]);
const ateliersParThematique = reactive<{ [key: string]: Atelier[] }>({});
interface DescriptionMap {
  [key: string]: string;
}

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
    setTimeout(() => {
      isLoading.value = false;
    }, 500);
  }
});

function organiserAteliersParThematique() {
  ateliers.value.forEach((atelier) => {
    const thematiqueId = atelier.thematique || 'Autres'; // Utilisez un identifiant unique pour chaque thématique
    if (!ateliersParThematique[thematiqueId]) {
      ateliersParThematique[thematiqueId] = []; // Créez un tableau vide si la thématique n'existe pas encore
    }
    ateliersParThematique[thematiqueId].push(atelier); // Ajoutez l'atelier au tableau correspondant à son identifiant de thématique
  });
}
</script>

<template>
  <div class="text-gray-800">
    <div v-if="isLoading" class="loading-overlay text-6xl">
      <font-awesome-icon :icon="'wine-bottle'" class="loading-icon"/>
    </div>
    <div v-else>
      <template v-for="thematique in Object.keys(ateliersParThematique)" :key="thematique">
        <div class="atelier-group px-4 py-4">
          <h1 class="text-3xl font-bold text-center text-dark-red mb-10">{{ thematique }}</h1>
          <p class="my-4 mx-16 text-center font-semibold">
            {{ descriptionParThematique[thematique] }}
          </p>
          <div class="atelier-details-container">
            <template v-for="atelier in ateliersParThematique[thematique]" :key="atelier.id">
              <div class="atelier-details">
                <AtelierCard :nom="atelier.nom" :description="atelier.description" :thematique="atelier.thematique" :id="atelier.id"/>
              </div>
            </template>
          </div>
        </div>
      </template>
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



