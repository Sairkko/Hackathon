<template>
  <div id="calendrier-page" class="p-10">
    <ConfirmDialog></ConfirmDialog>
    <h2 class="text-2xl font-bold">Calendrier des ateliers</h2>
    <FullCalendar :options="calendarOptions" />
    <Sidebar 
      v-model:visible="visibleRight" 
      :header="event.id ? 'Détail d\'un évènement' : 'Création d\'un nouvel évènement'" 
      position="right" 
      class="w-full md:w-1/2 lg:w-1/3">
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
          <label for="date_inscription_maximum">Date d'inscription max</label>
          <Calendar id="date_inscription_maximum" v-model="event.date_inscription_maximum" />
        </div>
        <div class="mb-2">
          <label for="limite_participant">Limite de participants</label>
          <InputNumber id="limite_participant" v-model="event.limite_participant" :min="0" showButtons buttonLayout="horizontal"/>
        </div>
        <div class="mb-2">
          <label for="location">Lieu</label>
          <InputText id="location" v-model="event.location" />
        </div>
      </div>
      <div class="text-right mb-2">
        <StrokeButton :label="event.id ? 'Enregistrer' : 'Créer l\'événement'" :icon="event.id ? 'pi pi-pencil' : 'pi pi-plus'" class="mt-2" @click="sendEvent" />
      </div>
      
      <hr/>
      <div v-if="event.reservations">
        <div class="text-center py-4" >
          <h4 class="text-xl">Participants ({{nbParticipants+"/"+event.limite_participant}})</h4>
        </div>
        <div class="text-right mb-2">
          <InscriptionForm :eventId="event.id" @addParticipation="el => event.reservations.push(el)" />
        </div>
        <p class="text-dark-red">Participants confirmés :</p>
        <div  v-for="user in event.reservations.filter(el => el.is_paid)" :key="user.id" class="flex px-1 justify-between">
          <span>
            {{user.name + " / " + user.email + " / " + user.nombre_participant + "p."}}
          </span>
          <font-awesome-icon class="text-red hover:text-red-600" icon="times" @click="deleteReservation(user)"></font-awesome-icon>
        </div>
        
        <p class="text-dark-red mt-3">Participants en attente :</p>
        <div v-for="user in event.reservations.filter(el => !el.is_paid)" :key="user.id" class="flex px-1 justify-between mb-2">
          <span>
            {{user.name + " / " + user.email + " / " + user.nombre_participant + "p."}}
          </span>
          <div>
            <font-awesome-icon class="text-green hover:text-blue-cyan mr-3" icon="check" @click="validateReservation(user)"></font-awesome-icon>
            <font-awesome-icon class="text-red hover:text-red-600" icon="times" @click="deleteReservation(user)"></font-awesome-icon>
          </div>
          
        </div>
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
import ReservationApi from "../api/ReservationApi"
import { useConfirm } from "primevue/useconfirm";
import { useToast } from "primevue/usetoast";
import InputNumber from "primevue/inputnumber"
import InscriptionForm from "../components/InscriptionForm.vue";
// import { getCurrentInstance } from 'vue'
import router from "../router";

export default defineComponent({
  name: "LoginPage",
  components: {
    FullCalendar,
    Sidebar,
    Calendar,
    InputText,
    // Button,
    StrokeButton,
    Dropdown,
    InputNumber,
    InscriptionForm
  },
  setup() {
    const usersStore = useUserStore();
    // const toast = useToast();
    const visibleRight = ref<boolean>(false)
    const event = ref<any>({})
    const events = ref<Event[]>([])
    const ateliers = ref<Atelier[]>([])
    const confirm = useConfirm();
    const toast = useToast();
    const loading = ref<boolean>(false)

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
      event.value = new Event()
      event.value.id = clickInfo.event.id
      event.value.atelier = clickInfo.event._def.extendedProps.atelier.id
      event.value.date_debut = new Date(clickInfo.event._def.extendedProps.date_debut.date)
      event.value.date_fin = new Date(clickInfo.event._def.extendedProps.date_fin.date)
      event.value.limite_participant = clickInfo.event._def.extendedProps.limite_participant
      if(clickInfo.event._def.extendedProps.date_inscription_maximum){
        event.value.date_inscription_maximum = new Date(clickInfo.event._def.extendedProps.date_inscription_maximum.date)
      }
      event.value.localisation = clickInfo.event._def.extendedProps.localisation
      visibleRight.value = true;
      ReservationApi.reservationByEvenement(event.value.id).then(response => {
        event.value.reservations = response.data.data.users.map((resa: any) => Object.assign({}, resa))
      }) 
    };

    const nbParticipants = computed(() => {
      let compter = 0
      event.value.reservations.forEach((el: any) => {
        if(el.is_paid){
          compter += el.nombre_participant
        }
      })
      return compter
    })

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
        
      })

    const deleteReservation = (reservation: any) => {
      confirm.require({
      message: 'Voulez vous supprimer cette réservation?',
      header: 'Annuler une réservation',
      icon: 'pi pi-info-circle',
      rejectLabel: 'Annuler',
      acceptLabel: 'Supprimer',
      rejectClass: 'p-button-secondary p-button-outlined',
      acceptClass: 'p-button-danger',
      accept: () => {
        const index = event.value.reservations.findIndex((el: any) => el.reservationId === reservation.reservationId && el.userId === reservation.userId)
        event.value.reservations.splice(index, 1)
        ReservationApi.deleteOneReservation(reservation.reservationId,reservation.userId).then(() => {
          toast.add({ severity: 'info', summary: 'Confirmé', detail: 'Réservation supprimée', life: 3000 });
        })
      },
      reject: () => {
          // toast.add({ severity: 'error', summary: 'Rejected', detail: 'You have rejected', life: 3000 });
      }
      });
        
        
    }

    const validateReservation = (reservation: any) => {
      confirm.require({
      message: 'Etes-vous sur de vouloir valider cette réservation?',
      header: 'Validation de payement',
      icon: 'pi pi-exclamation-triangle',
      rejectClass: 'p-button-secondary p-button-outlined',
      rejectLabel: 'Annuler',
      acceptLabel: 'Valider',
      accept: () => {
        ReservationApi.validPaiement(reservation.reservationId).then(() => {
          toast.add({ severity: 'info', summary: 'Confirmé', detail: 'Vous avez validé une réservation', life: 3000 });
        })
        reservation.is_paid = true;
          
      },
      reject: () => {
          // toast.add({ severity: 'error', summary: 'Rejected', detail: 'You have rejected', life: 3000 });
      }
    }); 
    }

    
    // const instance = getCurrentInstance();
      
    const sendEvent = () => {
      loading.value = true
      event.value.date_debut = new Date(event.value.date_debut).toISOString().slice(0, 19).replace('T', ' ');
      event.value.date_fin = new Date(event.value.date_fin).toISOString().slice(0, 19).replace('T', ' ');
      if(event.value.date_inscription_maximum){
        event.value.date_inscription_maximum = new Date(event.value.date_inscription_maximum).toISOString().slice(0, 19).replace('T', ' ');
      }
      if(event.value.id){
        EventApi.editEvent(event.value).then(() => {
          toast.add({ severity: 'info', summary: 'Enregistré', detail: 'Evènement modifié', life: 3000 });
          loading.value = false;
          router.go(0);
        })
      }else{
        EventApi.createEvent(event.value).then(() => {
          toast.add({ severity: 'info', summary: 'Enregistré', detail: 'Evènement crée', life: 3000 });
          loading.value = false;
          // event.value = Object.assign(new Event(), response.data.data)
          // events.value.push(event.value)
          router.go(0);
        })
      }
    }

    return {
      appUser,
      calendarOptions,
      visibleRight,
      event,
      ateliers,
      nbParticipants,
      deleteReservation,
      validateReservation,
      sendEvent,
    };
  },
});
</script>

<style scoped>

</style>