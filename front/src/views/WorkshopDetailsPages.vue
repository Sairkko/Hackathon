<template>
  <div class="relative min-h-screen bg-no-repeat bg-cover bg-fixed">
      <img src="@/assets/Fond.png" alt="Background" class="w-full object-cover" style="height: 470px;"/>
      <div class="absolute inset-0 flex items-center justify-center" style="height: 470px;">
        <div class="text-center text-white">
          <h1 class="text-3xl md:text-4xl font-bold" v-if="atelier.thematique && atelier.nom">{{ atelier.thematique }}</h1>
          <p class="text-sm md:text-lg mt-2" v-if="atelier.thematique && atelier.nom">{{ atelier.nom }}.</p>
        <div v-if="!atelier.thematique || !atelier.nom">
          <h1 class="text-3xl md:text-4xl font-bold">Thématique non disponible</h1>
        </div>
      </div>
      </div>
      <section class="px-10 pt-24 pb-24 bg-black">
          <div class="grid lg:grid-cols-3 items-center justify-items-center text-left">
              <div class="shadow-2xl lg:col-span-1">
                  <img src="@/assets/imageVin.png" alt="Image du Vin" class="mx-auto">
              </div>
              <div class="md:flex md:space-x-8 lg:col-span-2">
                <div class="md:flex-1">
                  <p v-if="atelier.description">{{ atelier.description }}</p>
                  <p v-else>Description non disponible</p>
                </div>
                  <div class="md:flex-1 border-l-2 border-lite-grey pl-4">
                      <h2 class="text-xl font-semibold mb-4">Prochaines sessions</h2>
                      <ul class="list-none pl-5 mb-4" v-for="session in atelier.ateliers" :key="session.id">
                          <li class="text-red">
                              <font-awesome-icon :icon="['fas', 'calendar-days']" />
                              <span class="text-dark-red pl-2" v-if="session.date_debut">
                                  {{ new Date(session.date_debut).toLocaleDateString() }} à {{ new Date(session.date_debut).toLocaleTimeString() }}
                              </span>
                              <span class="text-dark-red pl-2" v-else>Date non disponible</span>
                          </li>
                          <li class="text-red">
                              <font-awesome-icon :icon="['fas', 'location-dot']" />
                              <span class="text-dark-red pl-2">{{ session.localisation || 'Quelque part loin de tout, Lyon, France' }}</span>
                          </li>
                          <li class="mt-2 mb-5">
                              <Button @click="openDialog(session)" label="Participer" class="bg-red px-2 py-0.5" />
                          </li>
                      </ul>
                      <Dialog v-model:visible="display" :style="{ width: '50vw' }" :header="selectedAtelier ? 'Réserver Atelier #' + selectedAtelier.id : 'Réserver Atelier'">
                        <div class="grid grid-cols-2 gap-4">
                          <div>
                            <label for="reservationCount">Nombre de réservations:</label>
                            <input type="number" id="reservationCount" v-model="reservation.nombre_participant" class="input-field" placeholder="Nombre de personnes">
                          </div>
                          <div>
                            <template v-if="selectedAtelier && hasSchool(selectedAtelier)">
                              <p class="text-red">Cet événement est réservé aux étudiants de l'école : {{ selectedAtelier.ecole.nom }}</p>
                              <p>Si vous n'appartenez pas à cette école, veuillez annuler ou indiquer votre classe.</p>
                              <label for="classe">Classe:</label>
                              <input type="text" id="classe" v-model="reservation.classe" class="input-field" placeholder="Votre classe">
                            </template>
                          </div>
                        </div>
                        <br>
                        <Button @click="reservate" label="Participer a cet evenement" class="bg-red px-2 py-0.5" />
                      </Dialog>
                  </div>
              </div>
          </div>
      </section>
  </div>
</template>

<script>
import Button from 'primevue/button';
import {onMounted, ref} from "vue";
import AtelierApi from "@/api/AtelierApi";
import Dialog from 'primevue/dialog';
import reservationApi from "@/api/ReservationApi";

export default {
  name: 'WorkshopDetailsPage',
  components: {
      Button,
      Dialog
  },
  props: ['id'],
  setup(props) {
      const atelier = ref({});
      const display = ref(false);
      const selectedAtelier = ref(null);
      const reservation = ref({
          user: JSON.parse(localStorage.getItem('user')).id,
          atelier: 0,
          classe: '',
          nombre_participant: 1
      });

      const openDialog = (atelier) => {
          selectedAtelier.value = atelier;
          display.value = true;
          reservation.value.atelier = atelier.id;
      };

      const hasSchool = (atelier) => {
          return atelier.ecole && Object.keys(atelier.ecole).length > 0;
      }

      const fetchContent = async () => {
          try {
              const response = await AtelierApi.getAtelierDetail(props.id, reservation.value.user);
              atelier.value = response.data.data;
          } catch (error) {
              console.error('Error fetching atelier details:', error);
          }
      };

      const reservate = () => {
         reservationApi.createReservation(reservation.value)
              .then(() => {
                  fetchContent()
                  display.value = false;
              })
              .catch(error => {
                  console.error('Error fetching events:', error);
              });
          console.log(reservation.value)
      }

      onMounted(fetchContent);

      return {
          atelier,
          display,
          selectedAtelier,
          reservation,
          openDialog,
          reservate,
          hasSchool
      };
  }
};
</script>

<style scoped>
.input-field {
  width: 100%;
  padding: 0.5rem;
  border: 1px solid #ccc;
  border-radius: 4px;
  outline: none;
}
</style>
