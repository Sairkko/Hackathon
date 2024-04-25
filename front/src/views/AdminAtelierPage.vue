<script setup lang="ts">
import { onMounted, ref } from 'vue';
import Atelier from '../models/Atelier';
import AtelierApi from '@/api/AtelierApi';
import Dialog from 'primevue/dialog';
import Button from 'primevue/button';
import InputText from 'primevue/inputtext';
import InputNumber from 'primevue/inputtext';
import MultiSelect from 'primevue/multiselect';
import Product from "@/models/Product";

const ateliers = ref<Atelier[]>([]);
const ateliersParThematique = ref<{ [key: string]: Atelier[] }>({});
const isLoading = ref(false);
const displayModal = ref(false);
const selectedAtelier: any = ref(null);
const allProducts = ref<{ label: string, value: string }[]>([]);
const selectedProducts: any = ref<Product[]>([]);

onMounted(async () => {
  isLoading.value = true;
  await loadAteliers();
  isLoading.value = false;
  const productsResponse = await AtelierApi.product();
  allProducts.value = productsResponse.data.data.map((p: Product) => ({
    label: p.name,
    value: p.id
  }));
});

async function loadAteliers() {
  try {
    const response = await AtelierApi.atelier();
    ateliers.value = response.data.data;
    organiserAteliersParThematique();
  } catch (error) {
    console.error('There has been a problem with your operation:', error);
  }
}

function organiserAteliersParThematique() {
  const newAteliersParThematique: any = {};
  ateliers.value.forEach((atelier) => {
    const thematique = atelier.thematique || 'Autres';
    if (!newAteliersParThematique[thematique]) {
      newAteliersParThematique[thematique] = [];
    }
    newAteliersParThematique[thematique].push(atelier);
  });
  ateliersParThematique.value = newAteliersParThematique;
}


async function handleDelete(atelierId: string) {
  isLoading.value = true;
  try {
    await AtelierApi.removeAtelier(atelierId);
    const response = await AtelierApi.atelier();
    ateliers.value = response.data.data;
    organiserAteliersParThematique();
    isLoading.value = false;
  } catch (error) {
    console.error('Failed to delete the atelier:', error);
  }
}

async function openModal(atelier: Atelier) {
  selectedAtelier.value = atelier;
  selectedProducts.value = atelier.products!.map(product => product.id);
  console.log(selectedProducts.value);
  displayModal.value = true;
}

async function saveAtelier() {
  isLoading.value = true;
  try {
    await AtelierApi.atelierById(
        selectedAtelier.value.id,
        selectedAtelier.value.thematique,
        selectedProducts.value,
        selectedAtelier.value.nom,
        selectedAtelier.value.description,
        selectedAtelier.value.prix
    );
    await loadAteliers();
    displayModal.value = false;
    isLoading.value = false;
  } catch (error) {
    console.error('Failed to save the atelier:', error);
  }
}
</script>


<template>
  <div class="bg-white text-gray-800">
    <div v-if="isLoading" class="loading-overlay">
      Chargement en cours...
    </div>
    <div v-else class="bg-white text-gray-800">
      <section class="px-4 py-16">
        <h1 class="text-3xl font-bold text-center text-dark-red mb-10">
          Gestion des ateliers
        </h1>
        <div class="flex flex-col items-end gap-4 mx-16">
          <button class="mx-4 max-w-4xl border-2 text-red border-red bg-white py-1 px-1 rounded hover:bg-red-700 transition duration-300 font-bold">+ Ajouter un atelier</button>
        </div>
        <div v-for="(ateliers, thematique) in ateliersParThematique" :key="thematique">
          <h2 class="text-2xl font-bold my-4 text-dark-red text-start mx-4 md:mx-auto max-w-4xl py-4">{{ `Type d'atelier ${thematique}` }}</h2>
          <div v-for="atelier in ateliers" :key="atelier.id" class="mb-4">
            <div class="flex flex-col md:flex-row overflow-hidden rounded-lg shadow-md mb-8 mx-auto max-w-sm md:max-w-4xl">
              <img class="w-full md:w-2/6 object-cover" src="../assets/atelier.png" alt="Wine tasting experience">
              <div class="w-full md:w-4/6 p-4 bg-white flex flex-row justify-between gap-4">
                <div class="flex flex-col w-full">
                  <h1 class="text-xl bg-white font-bold text-start text-red mb-2">{{ atelier.nom }}</h1>
                  <p class="text-dark-red bg-white text-start mb-4">{{ atelier.description }}</p>
                  <p class="text-dark-red bg-white text-start mb-4"><font-awesome-icon :icon="'coins'" class="mr-2 text-red" />{{ atelier.prix }} €</p>
                  <p class="text-dark-red bg-white text-start mb-4">
                    <font-awesome-icon :icon="'wine-bottle'" class="mr-2 text-red" />
                    <span v-if="atelier.products && atelier.products.length > 0">
                    {{ atelier.products.map(product => product.name).join(', ') }}
                  </span>
                    <span v-else>
                    Pas de produit(s) associé
                  </span>
                  </p>
                </div>
                <div class="flex flex-col justify-center gap-4">
                  <button @click="openModal(atelier)" class="border text-white border-blue-cyan bg-blue-cyan py-2 px-4 rounded hover:bg-blue-cyan-700 transition duration-300">Modifier</button>
                  <button @click="handleDelete(atelier.id!)" style="width: 160px" class="border text-white border-red bg-red py-2 px-4 rounded hover:bg-red-700 transition duration-300">Supprimer</button>
                </div>
              </div>
            </div>

          </div>
        </div>
      </section>
      <Dialog v-model:visible="displayModal" style="width: 450px" :modal="true" header="Modifier l'atelier">
        <div class="flex flex-col gap-4 py-2">
          <label for="nom-atelier" class="block text-sm font-medium text-black">Nom de l'atelier</label>
          <InputText id="nom-atelier" v-model="selectedAtelier.nom" placeholder="Entrez le nom de l'atelier" class="mt-1 block w-full px-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" />
          <label for="thematique-atelier" class="block text-sm font-medium text-black">Thématique</label>
          <InputText id="thematique-atelier" v-model="selectedAtelier.thematique" placeholder="Entrez la thématique" class="mt-1 block w-full px-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" />
          <label for="description-atelier" class="block text-sm font-medium text-black">Description</label>
          <InputText id="description-atelier" v-model="selectedAtelier.description" placeholder="Entrez la description de l'atelier" class="mt-1 block w-full px-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" />
          <label for="prix-atelier" class="block text-sm font-medium text-black">Prix</label>
          <InputNumber id="prix-atelier" type="number" v-model="selectedAtelier.prix" placeholder="Entrez la description de l'atelier" class="mt-1 block w-full px-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" />
          <label for="product-atelier" class="block text-sm font-medium text-black">Sélectionner des produits</label>
          <MultiSelect
          v-model="selectedProducts"
          :options="allProducts"
          optionLabel="label"
          optionValue="value"
          placeholder="Sélectionnez des produits"
          display="chip"
          class="mt-1 w-full px-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
          />
          <Button label="Sauvegarder" class="bg-red text-white p-4" @click="saveAtelier" />
        </div>
      </Dialog>
    </div>
  </div>
</template>

<style scoped>
.loading-overlay {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: rgba(0, 0, 0, 0.5);
  display: flex;
  justify-content: center;
  align-items: center;
  color: white;
  font-size: 2rem;
}
</style>
