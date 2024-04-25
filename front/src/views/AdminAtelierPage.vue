<script setup lang="ts">
import { onMounted, ref, computed } from 'vue';
import Atelier from '../models/Atelier';
import AtelierApi from '@/api/AtelierApi';
import Dialog from 'primevue/dialog';
import Button from 'primevue/button';
import InputText from 'primevue/inputtext';
import Dropdown from 'primevue/dropdown';
import MultiSelect from 'primevue/multiselect';
import Product from "@/models/Product";
import StrokeButton from "@/components/StrokeButton.vue";
import { useToast } from 'primevue/usetoast';

const ateliers = ref<Atelier[]>([]);
const selectedThematique = ref(null);
const ateliersParThematique = ref<{ [key: string]: Atelier[] }>({});
const isLoading = ref(false);
const displayModal = ref(false);
const isEditMode = ref(false)
const displayDeleteConfirmation = ref(false);
const selectedAtelier: any = ref(null);
const allProducts = ref<{ label: string, value: string }[]>([]);
const selectedProducts: any = ref<Product[]>([]);
const atelierToDelete = ref<null | string>(null);
const toast = useToast();
const isValidNom = ref(true);
const isValidDescription = ref(true);
const isValidPrix = ref(true);


const thematiqueOptionsDropdown = computed(() => [
  { label: 'Exploration Terrestre', value: 'Exploration Terrestre' },
  { label: 'Exploration Céleste', value: 'Exploration Céleste' }
]);

const thematiqueOptions = computed(() => {
  const allOption = { label: 'Tous', value: null }; // Option pour réinitialiser le filtre
  const thematiques = Object.keys(ateliersParThematique.value);
  const options = thematiques.map(thematique => ({ label: thematique, value: thematique }));
  return [allOption, ...options];
});

const filteredAteliers = computed(() => {
  if (!selectedThematique.value) {
    return ateliersParThematique.value;
  }
  return { [selectedThematique.value]: ateliersParThematique.value[selectedThematique.value] || [] };
});

function validateForm() {
  isValidNom.value = !!selectedAtelier.value.nom && selectedAtelier.value.nom.trim().length > 0;
  isValidDescription.value = !!selectedAtelier.value.description && selectedAtelier.value.description.trim().length > 0;
  isValidPrix.value = !!selectedAtelier.value.prix && !isNaN(Number(selectedAtelier.value.prix)) && Number(selectedAtelier.value.prix) > 0;

  return isValidNom.value && isValidDescription.value && isValidPrix.value;
}


onMounted(async () => {
  isLoading.value = true;
  await loadAteliers();
  setTimeout(() => {
    isLoading.value = false;
  }, 500);
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
  atelierToDelete.value = atelierId;
  displayDeleteConfirmation.value = true;
}

async function confirmDelete() {
  if (atelierToDelete.value) {
    isLoading.value = true;
    try {
      await AtelierApi.removeAtelier(atelierToDelete.value);
      toast.add({severity: 'success', summary: 'Atelier supprimé', detail: 'L\'atelier a été supprimé avec succès.', life: 3000});
      await loadAteliers();
      displayDeleteConfirmation.value = false;
      setTimeout(() => {
        isLoading.value = false;
      }, 500);
    } catch (error) {
      setTimeout(() => {
        isLoading.value = false;
      }, 500);
      console.error('Failed to delete the atelier:', error);
      toast.add({severity: 'error', summary: 'Échec de la suppression', detail: 'Échec de la suppression de l\'atelier.', life: 3000});
    }
  }
}

function cancelDelete() {
  displayDeleteConfirmation.value = false;
  atelierToDelete.value = null;
}

function openAddModal() {
  selectedAtelier.value = new Atelier(); // Assuming Atelier is a class, if not, set to a default object
  selectedProducts.value = [];
  isEditMode.value = false;
  displayModal.value = true;
}

// Adjust existing openModal function to handle edit mode
function openEditModal(atelier: Atelier) {
  selectedAtelier.value = {...atelier}; // Create a copy to avoid direct mutation
  selectedProducts.value = atelier.products?.map(product => product.id) || [];
  isEditMode.value = true;
  displayModal.value = true;
}

async function saveAtelier() {
  if (!validateForm()) {
    toast.add({ severity: 'error', summary: 'Entrée invalide', detail: 'Veuillez remplir correctement tous les champs obligatoires.', life: 3000 });
    return;
  }
  isLoading.value = true;
  try {
    const dataSelectedAtelier: Atelier = {
      thematique : selectedAtelier.value.thematique,
      products: selectedProducts.value,
      nom: selectedAtelier.value.nom,
      description: selectedAtelier.value.description,
      prix: selectedAtelier.value.prix
    };
    if (isEditMode.value && selectedAtelier.value?.id) {
      await AtelierApi.atelierById(selectedAtelier.value.id, dataSelectedAtelier);
      toast.add({severity: 'success', summary: 'Atelier mis à jour', detail: 'L\'atelier a été mis à jour avec succès.', life: 3000});
    } else {
      const dataSelectedAtelierPost: Atelier = {
        thematique : selectedAtelier.value.thematique,
        products: selectedProducts.value,
        nom: selectedAtelier.value.nom,
        description: selectedAtelier.value.description,
        prix: selectedAtelier.value.prix
      }
      await AtelierApi.postAtelier(dataSelectedAtelierPost);
      toast.add({severity: 'success', summary: 'Atelier ajouté', detail: 'Un nouvel atelier a été ajouté avec succès.', life: 3000});
    }
    await loadAteliers();
    displayModal.value = false;
    setTimeout(() => {
      isLoading.value = false;
    }, 500);
  } catch (error) {
    console.error('Failed to save the atelier:', error);
    setTimeout(() => {
      isLoading.value = false;
    }, 500);
    toast.add({severity: 'error', summary: 'Échec de la sauvegarde', detail: 'Échec de la sauvegarde de l\'atelier.', life: 3000});
  }
}
</script>


<template>
  <div class="text-gray-800">
    <div v-if="isLoading" class="loading-overlay text-6xl">
      <font-awesome-icon :icon="'wine-bottle'" class="loading-icon"/>
    </div>
    <div v-else>
      <section class="px-4 py-16">
        <h1 class="text-3xl font-bold text-center text-dark-red mb-10">
          Gestion des ateliers
        </h1>
        <div class="flex flex-col items-center md:items-end gap-4 max-w-4xl lg:mx-20">
          <StrokeButton icon="pi pi-plus" @click="openAddModal" label="Ajouter un atelier" ></StrokeButton>
        </div>
        <div class="flex justify-center md:justify-start mx-4 my-4 max-w-4xl lg:mx-20">
        <Dropdown v-model="selectedThematique" :options="thematiqueOptions" optionLabel="label" optionValue="value" placeholder="Sélectionnez une thématique" class="p-dropdown border text-dark-red" style="width: 300px" />
        </div>
        <div v-for="(ateliers, thematique) in filteredAteliers" :key="thematique">
          <h2 class="text-2xl font-bold my-4 text-dark-red text-start mx-4 md:mx-auto max-w-4xl py-4">{{ `Type d'atelier ${thematique}` }}</h2>
          <div v-for="atelier in ateliers" :key="atelier.id" class="mb-4">
            <div class="flex flex-col md:flex-row overflow-hidden rounded-lg shadow-md mb-8 mx-auto max-w-sm md:max-w-4xl">
              <img v-if="atelier.thematique === 'Exploration Céleste'"
                  class="w-full md:w-2/6 object-cover"
                  src="../assets/ExploType.png"
                  alt="Wine tasting experience"
              >
                <img v-else
                    class="w-full md:w-2/6 object-cover"
                    src="../assets/atelier.png"
                    alt="Wine tasting experience"
                >
                <div class="w-full md:w-4/6 p-4 bg-white flex flex-row justify-between gap-4">
                <div class="flex flex-col w-full">
                  <h1 class="text-xl bg-white font-bold text-start text-red mb-2">{{ atelier.nom }}</h1>
                  <p class="text-dark-red bg-white text-justify mb-4">{{ atelier.description }}</p>
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
                  <button @click="openEditModal(atelier)" class="border text-white border-blue-cyan bg-blue-cyan py-2 px-4 rounded hover:bg-blue-cyan-500 transition duration-300">Modifier</button>
                  <button @click="handleDelete(atelier.id!)" style="width: 160px" class="border text-white border-red bg-red py-2 px-4 rounded hover:bg-red-700 transition duration-300">Supprimer</button>
                </div>
              </div>
            </div>

          </div>
        </div>
      </section>
      <Dialog v-model:visible="displayModal" style="width: 450px" :modal="true" :header="isEditMode ? 'Modifier l\'atelier' : 'Ajouter un atelier'">
        <div class="flex flex-col gap-4 py-2">
          <label for="nom-atelier" class="block text-sm font-medium text-black">Nom de l'atelier <span class="text-danger">*</span></label>
          <InputText :class="{'border-danger': !isValidNom}" id="nom-atelier" v-model="selectedAtelier.nom" placeholder="Entrez le nom de l'atelier" class="mt-1 block w-full px-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required/>
          <label for="thematique-atelier" class="block text-sm font-medium text-black">Thématique</label>
          <Dropdown
              v-model="selectedAtelier.thematique"
              :options="thematiqueOptionsDropdown"
              optionLabel="label"
              optionValue="value"
              placeholder="Sélectionnez une thématique"
              class="mt-1 w-full px-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
          />
          <label for="description-atelier" class="block text-sm font-medium text-black">Description <span class="text-danger">*</span></label>
          <InputText :class="{'border-danger': !isValidDescription}" id="description-atelier" v-model="selectedAtelier.description" placeholder="Entrez la description de l'atelier" class="mt-1 block w-full px-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required/>
          <label for="prix-atelier" class="block text-sm font-medium text-black">Prix <span class="text-danger">*</span></label>
          <InputText :class="{'border-danger': !isValidPrix}" id="prix-atelier" type="number" v-model="selectedAtelier.prix" placeholder="Entrez la description de l'atelier" class="mt-1 block w-full px-3 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required/>
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
      <Dialog v-model:visible="displayDeleteConfirmation" style="width: 450px" :modal="true" header="Confirmer la suppression">
        <p>Êtes-vous sûr de vouloir supprimer cet atelier ?</p>
        <div class="flex justify-end gap-2">
          <Button label="Annuler" @click="cancelDelete" class="p-button-text bg-grey text-white m-4 py-1 px-2" />
          <Button label="Supprimer" class="p-button-danger bg-red text-white m-4 py-1 px-2" @click="confirmDelete" />
        </div>
      </Dialog>
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
