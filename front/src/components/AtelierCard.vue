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
      <StrokeButton label="Participer" @click="showModal = true" />

      <!-- Modal Dialogue -->
      <Dialog v-model:visible="showModal" style="width: 80vw" :modal="true">
        <template v-if="ateliers.length > 0">  <DataTable :value="ateliers">
          <Column field="date_debut" header="Début"></Column>
          <Column field="date_fin" header="Fin"></Column>
          <Column header="Localisation" :sortable="false">
            <template #body="slotProps">
              <span>{{ slotProps.data.localisation || 'Localisation inconnue' }}</span>
            </template>
          </Column>
          <Column header="Ecole" :sortable="false">
            <template #body="slotProps">
              <span>{{ slotProps.data.ecole.nom || 'Sans école' }}</span>
            </template>
          </Column>
          <Column header="Inscription">
            <template #body="slotProps">
              <StrokeButton @click="openDialog(slotProps.data)" label="Inscription" class="text-red"></StrokeButton>
            </template>
          </Column>
        </DataTable>
        </template>
        <div v-else class="text-center p-4"> <p>Pas d'événement disponible pour cet atelier.</p>
        </div>
      </Dialog>
      <Dialog v-model:visible="display" :style="{ width: '50vw' }" :header="selectedAtelier ? 'Réserver Atelier #' + selectedAtelier.id : 'Réserver Atelier'">
        <div class="grid grid-cols-2 gap-4">
          <div>
            <label for="reservationCount" class="font-bold">Nombre de réservations:</label>
            <input type="number" id="reservationCount" v-model="reservation.nombre_participant" class="input-field border rounded p-1" placeholder="Nombre de personnes">
          </div>
          <div>
            <template v-if="selectedAtelier && hasSchool(selectedAtelier)">
              <p class="text-red">Cet événement est réservé aux étudiants de l'école : {{ selectedAtelier.ecole.nom }}</p>
              <p>Si vous n'appartenez pas à cette école, veuillez annuler ou indiquer votre classe.</p>
              <label for="classe" class="font-bold">Classe:</label>

              <input type="text" id="classe" v-model="reservation.classe" class="input-field border p-1 rounded" placeholder="Votre classe">
            </template>
          </div>
        </div>
        <br>
        <Button @click="reservate" label="Participer a cet evenement" class="bg-red px-2 py-0.5" />
      </Dialog>
    </div>
  </div>
</div>
</template>

<script setup lang="ts">
import Atelier from '../models/Atelier';
import { ref, onMounted, computed } from 'vue';
import Dialog from 'primevue/dialog';
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import AtelierApi from '@/api/AtelierApi';
import StrokeButton from "@/components/StrokeButton.vue";

import { useUserStore } from '@/store/UserStrore';
import reservationApi from "@/api/ReservationApi";
import { useToast } from 'primevue/usetoast';
const toast = useToast();

const usersStore = useUserStore();
const user = computed(() => {
  return usersStore.getUser;
});

const showModal = ref(false);
const ateliers = ref([]);
const display = ref(false);
const selectedAtelier = ref(null);

const reservation = ref({
  user: user?.value?.id,
  atelier: 0,
  classe: '',
  nombre_participant: 1
});

const openDialog = (atelier: any) => {
  console.log(atelier);
  selectedAtelier.value = atelier;
  console.log(selectedAtelier.value);
  display.value = true;
  reservation.value.atelier = atelier.id;
};

const hasSchool = (atelier: any) => {
  return atelier.ecole && Object.keys(atelier.ecole).length > 0;
}

const reservate = () => {
  reservationApi.createReservation(reservation.value)
      .then(() => {
        toast.add({
          severity: 'success',
          summary: 'Réservation réussie',
          detail: 'Votre place a été réservée avec succès',
          life: 3000
        });
        fetchAtelierDetails(props.id, user?.value?.id)
            .then(() => {
              display.value = false;
            })
            .catch(error => {
              console.error('Error fetching atelier details:', error);
              toast.add({
                severity: 'error',
                summary: 'Détails non obtenus',
                detail: 'Impossible de récupérer les détails de l\'atelier',
                life: 3000
              });
            });
      })
      .catch(error => {
        console.error('Error creating reservation:', error);
        display.value = true; // Réafficher le formulaire en cas d'échec
        toast.add({
          severity: 'error',
          summary: 'Réservation non éffectuée',
          detail: 'La réservation n\'a pas pu être effectuée en raison d\'une erreur',
          life: 3000
        });
      });
}


async function fetchAtelierDetails(idAtelier: string, idUser: string | undefined) {
  try {
    const response = await AtelierApi.getAtelierDetail(idAtelier, idUser);
    ateliers.value = response.data.data.ateliers;
  } catch (error) {
    console.error('Error fetching atelier details:', error);
  }
}

onMounted(() => {
  fetchAtelierDetails(props.id, user?.value?.id);
});

// eslint-disable-next-line no-unused-vars,no-undef
const props = defineProps<{
  modelValue: Atelier,
  id: string,
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

