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
  <Button @click="click" class="my-button" :icon="icon" :label="label" />
</template>

<style scoped>
.cancel-button {
  @apply text-grey;
  color: #fff; /* Texte blanc */
  padding-top: 0.5rem; /* Padding vertical de 0.5rem */
  padding-bottom: 0.5rem; /* Padding vertical de 0.5rem */
  padding-left: 1rem; /* Padding horizontal de 5rem */
  padding-right: 1rem; /* Padding horizontal de 5rem */
  
}

.cancel-button:hover {
  @apply bg-hover-grey
}
</style>

