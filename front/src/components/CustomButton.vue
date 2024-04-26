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
});
</script>


<template>
  <Button class="my-button" :icon="icon" :label="label" />
</template>

<style scoped>
.my-button {
  background-color: #C10041;
  color: #fff; /* Texte blanc */
  padding-top: 0.5rem; /* Padding vertical de 0.5rem */
  padding-bottom: 0.5rem; /* Padding vertical de 0.5rem */
  padding-left: 1rem; /* Padding horizontal de 5rem */
  padding-right: 1rem; /* Padding horizontal de 5rem */
}

.my-button:hover {
  @apply bg-hover-red
}
</style>

