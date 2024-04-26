<script lang="ts">
import Button from "primevue/button"
import Dialog from "primevue/dialog"
import InputText from "primevue/inputtext"
import { defineComponent, ref } from "vue";
import ReservationApi from "../api/ReservationApi";
import StrokeButton from "./StrokeButton.vue";
import InputNumber from "primevue/inputnumber"

export default defineComponent({
  name: "InscriptionPage",
  props: {
    eventId: {
      type: String
    }
  },
  components: {
    Button,
    StrokeButton,
    Dialog,
    InputNumber,
    InputText
  },
  setup(props, {emit}) {
    const is_visible = ref(false);
    const newInscription = ref<any>({})
    newInscription.value.atelierId = props.eventId!

    const saveInscription = () => {
      is_visible.value = false;
      console.log(newInscription.value)
      ReservationApi.createUserWithReservation(newInscription.value).then((response: any) => {
        console.log(response.data.data);
        
        emit('addParticipation', response.data.data)
      })
    }
    return {
      is_visible,
      newInscription,
      saveInscription
    };
  },
});
</script>


<template>
  <div>
  <StrokeButton label="Ajouter un participant" icon="pi pi-plus" @click="is_visible = true" />
      <Dialog v-model:visible="is_visible" header="Ajouter un participant" :style="{ width: '35rem' }" modal>
          <span class="p-text-secondary block mb-5">coordonnées du participant.</span>
          <div class="flex align-items-center gap-3 mb-3">
              <label for="lastName" class="font-semibold w-1/4">Nom *</label>
              <InputText id="lastName" class="flex-auto" autocomplete="off" v-model="newInscription.nom" />
          </div>
          <div class="flex align-items-center gap-3 mb-5">
              <label for="firstName" class="font-semibold w-1/4">Prenom *</label>
              <InputText id="firstName" class="flex-auto" autocomplete="off" v-model="newInscription.prenom" />
          </div>
          <div class="flex align-items-center gap-3 mb-5">
              <label for="email" class="font-semibold w-1/4">email *</label>
              <InputText id="email" class="flex-auto" autocomplete="off" v-model="newInscription.mail" />
          </div>
          <div class="flex align-items-center gap-3 mb-5">
              <label for="telephone" class="font-semibold w-1/4">telephone</label>
              <InputText id="telephone" class="flex-auto" autocomplete="off" v-model="newInscription.telephone"/>
          </div>
          <div class="flex align-items-center gap-3 mb-5">
              <label for="nb_participants" class="font-semibold w-1/4">Nombre participants</label>
              <InputNumber id="limite_participant" class="flex-auto" v-model="newInscription.nb_participants" :min="0" showButtons buttonLayout="horizontal"/>
              <!-- <I id="nb_participants" class="flex-auto" autocomplete="off" v-model="newInscription.nb_participants"/> -->
          </div>
          <div class="flex justify-content-end gap-2">
              <Button type="button" label="Cancel" severity="secondary" @click="visible = false"></Button>
              <Button type="button" label="Save" @click="saveInscription"></Button>
          </div>
      </Dialog>
    </div>
</template>

<style scoped>
</style>

