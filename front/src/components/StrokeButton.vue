<script lang="ts">
import Button from "primevue/button"
import { defineComponent } from "vue";

export default defineComponent({
  name: "LoginPage",
  props: {
    icon: {
      type: String
    },
    label: {
      type: String
    }
  },
  components: {
    Button
  },
  setup(props,{emit}) {
    
    const click = () => {
      emit('click')
    }
    return {
      click
    };
  },
});
</script>


<template>
  <Button @click="click" class="custom-button" :icon="icon" :label="label" />
</template>

<style scoped>
.custom-button {
  background-color: transparent;  /* Aucun fond pour que le bouton soit transparent */
  color: #e91e63;                /* Couleur rose spécifique pour le texte */
  border: 2px solid #e91e63;     /* Bordure rose */
  padding: 5px 10px;            /* Espace intérieur pour que le texte ne touche pas les bords */
}

.custom-button:hover {
  @apply hover:bg-red-100 ;
}
</style>

