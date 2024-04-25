<template>
  <div id="calendrier-page" class="p-10">
    <h2 class="text-2xl font-bold">Calendrier des ateliers</h2>
    <FullCalendar :options="calendarOptions" />
    <Sidebar v-model:visible="visibleRight" :header="event.id ? 'Détail d\'un évènement' : 'Création d\'un nouvel évènement'" position="right" class="w-1/3">
      <div class="p-fluid p-grid">
        <div class="mb-2">
          <label for="atelier">Choix de l'atelier</label>
          <Dropdown id="atelier" v-model="event.atelier" :options="ateliers" optionValue="id" optionLabel="nom"/>
        </div>
        <div class="md:flex gap-2">
          <div class="mb-2 w-full md:w-1/2">
            <label for="startTime">Début</label>
            <Calendar id="startTime" v-model="event.date_debut" showTime />
          </div>
          <div class="mb-2 w-full md:w-1/2">
            <label for="endTime">Fin</label>
            <Calendar id="endTime" v-model="event.date_fin" showTime  />
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
import AtelierApi from "../api/AtelierApi"
import Atelier from "../models/Atelier";
import Dropdown from "primevue/dropdown"

export default defineComponent({
  name: "LoginPage",
  components: {
    FullCalendar,
    Sidebar,
    Calendar,
    InputText,
    // Button,
    StrokeButton,
    Dropdown
  },
  setup() {
    const usersStore = useUserStore();
    // const toast = useToast();
    const visibleRight = ref<boolean>(false)
    const event = ref<any>({})
    const events = ref<Event[]>([])
    const ateliers = ref<Atelier[]>([])

    const appUser = computed(() => {
      return usersStore.getUser;
    });

    EventApi.getEvents().then(response => {
      events.value = response.data.data.map((event: Event) => Object.assign(new Event(), event))
      calendarOptions.value.events = events.value
    })

    AtelierApi.atelier().then(response => {
      ateliers.value = response.data.data.map((atelier: Atelier) => Object.assign(new Atelier(), atelier))
    })


    const handleEventClick = (clickInfo: any) => {
      event.value.id = clickInfo.event.id
      EventApi.getEvent(event.value).then(response => {
        console.log(response)
      })
      // event.value = new Event()
      // event.value.id = clickInfo.event.id
      // event.value.atelier = clickInfo.event._def.extendedProps.atelier.id
      // event.value.date_debut = new Date(clickInfo.event._def.extendedProps.date_debut.date)
      // event.value.date_fin = new Date(clickInfo.event._def.extendedProps.date_fin.date)
      // event.value.date_inscription_maximum = new Date(clickInfo.event._def.extendedProps.date_inscription_maximum.date)
      // event.value.localisation = clickInfo.event._def.extendedProps.localisation
      // visibleRight.value = true;
      // ReservationApi.getReservations
      // event.value.reservations = 
    };

    const calendarOptions = ref<any>({
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
              if(event.value.id){
                event.value = new Event()
              }
              visibleRight.value = !visibleRight.value
            }
          }
        },
        headerToolbar: {
          center: 'addButton',
          right: 'dayGridMonth,timeGridWeek'
        },
        events: [],
        eventClick: handleEventClick ,
         dayMaxEvents: 5,
        eventContent: function(arg: any) {
          if (arg.isMirror) {
            return { domNodes: [] };
          }
          if (arg.isStart) {
            return { html: `<span class='event-title'>${arg.event.title}</span>` };
          } else {
            return { domNodes: [] };
          }
        },
      })

    return {
      appUser,
      calendarOptions,
      visibleRight,
      event,
      ateliers
    };
  },
});
</script>

<style scoped>

</style>