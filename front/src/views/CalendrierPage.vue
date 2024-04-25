<template>
  <div id="calendrier-page" class="p-10">
    <h2 class="text-2xl font-bold">Calendrier des ateliers</h2>
    <FullCalendar :options="calendarOptions" />
    <Sidebar v-model:visible="visibleRight" header="Création d'un nouvel évènement" position="right" class="w-1/3">
      <div class="p-fluid p-grid">
        <div class="mb-2">
          <label for="atelier">Choix de l'atelier</label>
          <InputText id="atelier" v-model="event.atelier" />
        </div>
        <div class="md:flex gap-2">
          <div class="mb-2 w-full md:w-2/4">
            <label for="date">Date</label>
            <Calendar id="date" v-model="event.date" />
          </div>
          <div class="mb-2 w-full md:w-1/4">
            <label for="startTime">Début</label>
            <Calendar id="startTime" v-model="event.startTime" timeOnly />
          </div>
          <div class="mb-2 w-full md:w-1/4">
            <label for="endTime">Fin</label>
            <Calendar id="endTime" v-model="event.endTime" timeOnly />
          </div>
        </div>
        <div class="mb-2">
          <label for="inscriptionDate">Date d'inscription max.</label>
          <Calendar id="inscriptionDate" v-model="event.inscriptionDate" />
        </div>
        <div class="mb-2">
          <label for="location">Lieu</label>
          <InputText id="location" v-model="event.location" />
        </div>
      </div>
      <div class="text-right mb-2">
        <StrokeButton label="Créer l'événement" icon="pi pi-plus" class="mt-2" />
      </div>
      
      <hr/>
      <div class="text-center py-4">
        <h4>Participants ({{1+"/"+"18"}})</h4>
      </div>
      
    </Sidebar>
  </div>
</template>

<script lang="ts">
// import { useToast } from "primevue/usetoast";
import { defineComponent, computed, ref } from "vue";
import { useUserStore } from "../store/UserStrore";
import FullCalendar from '@fullcalendar/vue3'
import dayGridPlugin from '@fullcalendar/daygrid'
import timeGridWeek from '@fullcalendar/timegrid';
import interactionPlugin from '@fullcalendar/interaction'
import Sidebar from 'primevue/sidebar'
import Calendar from 'primevue/calendar'
import InputText from 'primevue/inputtext'
// import Button from 'primevue/button'
import StrokeButton from "../components/StrokeButton.vue";
import EventApi from "../api/EventApi"
import Event from "../models/Event"

export default defineComponent({
  name: "LoginPage",
  components: {
    FullCalendar,
    Sidebar,
    Calendar,
    InputText,
    // Button,
    StrokeButton
  },
  setup() {
    const usersStore = useUserStore();
    // const toast = useToast();
    const visibleRight = ref<boolean>(false)
    const event = ref<any>({})
    const events = ref<Event[]>([])

    const appUser = computed(() => {
      return usersStore.getUser;
    });

    const calendarOptions = {
        plugins: [ dayGridPlugin, interactionPlugin, timeGridWeek ],
        initialView: 'dayGridMonth',
        locale: 'fr',
        buttonText: {
          today: 'Aujourd\'hui',
          month: 'Mois',
          week: 'Semaine',
          day: 'Jour'
        },
        customButtons: {
          addButton: {
            color: "#C10041",
            text: 'Ajouter un Atelier',
            click: function() {
              visibleRight.value = !visibleRight.value
            }
          }
        },
        headerToolbar: {
          center: 'addButton',
          right: 'dayGridMonth,timeGridWeek'
        },
      }

      EventApi.getEvents().then(data => {
        events.value = data.data.map((event: Event) => Object.assign(new Event(), event))
      })

    return {
      appUser,
      calendarOptions,
      visibleRight,
      event
    };
  },
});
</script>

<style scoped>

</style>