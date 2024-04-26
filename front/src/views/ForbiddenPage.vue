<template>
  <div class="flex items-center justify-center h-screen bg-gray-100 text-gray-800">
    <div class="text-center">
      <h1 class="text-4xl font-bold text-red-700 mb-4">Accès Refusé</h1>
      <p class="mb-6">Vous n'avez pas la permission d'accéder à cette page.</p>
      <CustomButton label="Retour à l'accueil"  @click="goHome" />
    </div>
  </div>
</template>

<script lang="ts">
import { defineComponent } from 'vue';
import { useRouter } from 'vue-router';
import CustomButton from "../components/CustomButton.vue"

export default defineComponent({
  name: "ForbiddenPage",
  components: {
    CustomButton
  },
  setup() {
    const router = useRouter();

    function goHome() {
      router.push('/');
    }

    return {
      goHome,
    };
  },
});
</script>