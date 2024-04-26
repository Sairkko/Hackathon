<template>
    <div id="my-reservation">
      <div v-if="isLoading" class="loading-overlay text-6xl">
        <font-awesome-icon :icon="'wine-bottle'" class="loading-icon"/>
      </div>
      <div v-else>
        <section class="px-4 py-16">
            <h1 class="text-3xl font-bold text-center text-dark-red mb-10">Mes réservations en attentes de paiement</h1>
            <p class="mt-4 mx-16 mb-10 text-center font-semibold">
                Pour pouvoir reserver sa place envoie le prix à ce paypal: atelier-vins@olivier-bonneton.com
            </p>
            <template v-if="unpaidReservations.length">
                <div v-for="event in unpaidReservations" :key="event.id" class="flex flex-row overflow-hidden rounded-lg shadow-md mb-8 mx-4 md:mx-auto max-w-4xl">
                    <img class="w-full md:w-2/5 lg:w-1/5 object-cover" src="../assets/atelier.png" alt="Wine tasting experience">
                    <div class="w-full md:w-3/5 lg:w-4/5 p-4 bg-white flex flex-row justify-between gap-4">
                        <div class="flex flex-col">
                            <h3 class="text-xl font-bold text-start text-red">{{ event.atelier_content.nom }}</h3>
                            <p class="text-dark-red text-start mb-4">
                                {{ formatDate(event.date_debut) }} <br> Durée: {{ calculateDuration(event.date_debut, event.date_fin) }}
                            </p>
                            <p class="text-red text-start mb-4">
                                <font-awesome-icon :icon="'hourglass-start'" />
                                En attente de paiement
                            </p>

                        </div>
                        <div class="flex flex-col justify-center gap-4">
                            <button @click="confirmDelete(event.reservation.id)" style="width: 160px" class="border text-white border-grey bg-grey py-2 px-4 rounded hover:bg-red-700 transition duration-300">Annuler</button>
                            <a :href="`/atelier/${event.atelier_content.id}`" style="width: 160px" class="border text-white border-red bg-red py-2 px-4 rounded hover:bg-red-700 transition duration-300">Détails</a>
                        </div>
                    </div>
                </div>
            </template>
            <p v-else class="text-center">Aucune réservation en attente de paiement.</p>
        </section>
      <Dialog v-model:visible="showDialog" header="Confirmer la suppression" :modal="true">
        <p>Voulez-vous vraiment annuler cette réservation ?</p>
        <div class="flex justify-end gap-2">
          <Button label="Annuler" @click="showDialog = false" class="p-button-text bg-grey text-white m-4 py-1 px-2" />
          <Button label="Supprimer" class="p-button-danger bg-red text-white m-4 py-1 px-2" @click="deleteReservation" />
        </div>
      </Dialog>

        <section class="px-4 mb-10">
            <h2 class="text-3xl font-bold text-center text-dark-red mb-10">Mes réservations validées</h2>
            <p>Une fois la resservation validé la validation n'est pas disponible. Pour plus de précision veuillez contacter bonnetonolivier@gmail.com</p>
            <template v-if="paidReservations.length">
                <div v-for="event in paidReservations" :key="event.id" class="flex flex-row overflow-hidden rounded-lg shadow-md mb-8 mx-4 md:mx-auto max-w-4xl">
                    <img class="w-full md:w-2/5 lg:w-1/5 object-cover" src="../assets/atelier.png" alt="Wine tasting experience">
                    <div class="w-full md:w-3/5 lg:w-4/5 p-4 bg-white flex flex-row justify-between gap-4">
                        <div class="flex flex-col">
                            <h3 class="text-xl font-bold text-start text-red">{{ event.atelier_content.nom }}</h3>
                            <p class="text-dark-red text-start mb-4">
                                {{ formatDate(event.date_debut) }} <br><x></x> Durée: {{ calculateDuration(event.date_debut, event.date_fin) }}
                            </p>
                            <p class="text-green text-start mb-4">
                                <font-awesome-icon :icon="'check'" />
                                Réservation effectuée
                            </p>
                        </div>
                        <div class="flex flex-col justify-center gap-4">
                            <a :href="`/atelier/${event.atelier_content.id}`" style="width: 160px" class="border text-white border-red bg-red py-2 px-4 rounded hover:bg-red-700 transition duration-300">Détails</a>
                        </div>
                    </div>
                </div>
            </template>
            <p v-else class="text-center">Aucune réservation validée.</p>
        </section>
      </div>
    </div>
</template>

<script>
import {ref, computed, onMounted} from 'vue';
import Toast from 'primevue/toast';
import Dialog from 'primevue/dialog';
import Button from 'primevue/button';
import userApi from "@/api/UserApi";
import reservationApi from "@/api/ReservationApi";
import {differenceInMinutes, format} from 'date-fns';
import {useToast} from "primevue/usetoast";
export default {
  components: {
    Dialog,
    Button,
    // eslint-disable-next-line vue/no-unused-components
    Toast
  },
    setup() {
      const toast = useToast();
      const showDialog = ref(false);
      const reservationToDelete = ref(null);
      const isLoading = ref(false);

      const events = ref([]);

        const paidReservations = computed(() =>
            events.value.filter(event => event.reservation && event.reservation.is_paid)
        );
        const unpaidReservations = computed(() =>
            events.value.filter(event => event.reservation && !event.reservation.is_paid)
        );

      const formatDate = (dateObj) => {
        const dateString = dateObj.date || dateObj;

        const dateStr = String(dateString);

        const dateWithoutMicroseconds = dateStr.replace(/\.\d+/, '');

        const date = new Date(dateWithoutMicroseconds);

        if (Number.isNaN(date.getTime())) {
          console.warn('Invalid date passed to formatDate:', dateString);
          return 'Invalid date'; // Ou tout autre texte de remplacement que vous préférez
        }

        return format(date, 'dd-MM-yyyy à HH:mm');
      };

      const calculateDuration = (startDateObj, endDateObj) => {
        // Extraire la propriété 'date' de l'objet, si 'startDateObj' et 'endDateObj' sont des objets
        const startDateString = startDateObj.date || startDateObj;
        const endDateString = endDateObj.date || endDateObj;

        // Convertir les dates en chaînes si ce ne sont pas déjà des chaînes
        const startDateStr = String(startDateString);
        const endDateStr = String(endDateString);

        const startDateWithoutMicroseconds = startDateStr.replace(/\.\d+/, '');
        const endDateWithoutMicroseconds = endDateStr.replace(/\.\d+/, '');

        const start = new Date(startDateWithoutMicroseconds);
        const end = new Date(endDateWithoutMicroseconds);

        // Vérifier si les dates sont valides
        if (Number.isNaN(start.getTime()) || Number.isNaN(end.getTime())) {
          console.warn('Invalid start or end date passed to calculateDuration:', startDateStr, endDateStr);
          return 'Invalid duration'; // Ou tout autre texte de remplacement que vous préférez
        }

        const diffMinutes = differenceInMinutes(end, start);
        const hours = Math.floor(diffMinutes / 60);
        const minutes = diffMinutes % 60;
        return `${hours}h${minutes < 10 ? '0' + minutes : minutes}`;
      };

      const userStr = localStorage.getItem('user');
        const user = JSON.parse(userStr);
        const userId = user.id;

        onMounted(async () => {
          isLoading.value = true;
          await fetchReservation()
          setTimeout(() => {
            isLoading.value = false;
          }, 500);
        })

      const confirmDelete = (reservationId) => {
        reservationToDelete.value = reservationId;
        showDialog.value = true;
      };

      const deleteReservation = async () => {
        isLoading.value = true;
        if (reservationToDelete.value) {
          await deleteOneReservation(reservationToDelete.value); // Attendre que la suppression soit terminée
          await fetchReservation();
          showDialog.value = false; // Fermer le dialogue
          setTimeout(() => {
            isLoading.value = false;
          }, 500);
        } else {
          setTimeout(() => {
            isLoading.value = false;
          }, 500);
        }
      };

      const fetchReservation = async () => {
        if (userId) {
          try {
            const response = await userApi.getEvenementByUser(userId);
            events.value = response.data.data;
            console.log("Reservations updated", events.value);
          } catch (error) {
            console.error('Error fetching events:', error);
            events.value = [];
          }
        } else {
          console.error("No user found in localStorage");
          events.value = [];
        }
      }

      const deleteOneReservation = async (reservationId) => {
        const user = JSON.parse(userStr);
        const userId = user.id;
        try {
          await reservationApi.deleteOneReservation(reservationId, userId);
          toast.add({severity:'success', summary:'Supprimé', detail:'Réservation supprimée avec succès.', life: 3000});
          await fetchReservation();
        } catch (error) {
          console.error('Error deleting reservation:', error);
          toast.add({severity:'error', summary:'Erreur', detail:'Échec de la suppression de la réservation.', life: 3000});
        }
      };


      return {
            showDialog,
            reservationToDelete,
            confirmDelete,
            deleteReservation,
            paidReservations,
            unpaidReservations,
            events,
            deleteOneReservation,
            formatDate,
            calculateDuration,
            isLoading
        };
    }
}
</script>


<style>
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
