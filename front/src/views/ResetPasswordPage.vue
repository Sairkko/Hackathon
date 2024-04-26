<template>
  <div class="flex items-center justify-center h-screen">
    <div class="w-full max-w-md">
        <div class="mb-4">
          <img src="../assets/winApp.png" alt="winApp" class="w-80 mx-auto py-5"/>
        </div>
        <Card class="p-card-no-padding">
            <template #title>
                Mot de passe oublié
            </template>
            <template #content>
                <div class="px-8 pb-7">
                  <div class="text-center">
                    Saisissez votre adresse e-mail et nous vous enverrons des instructions pour réinitialiser votre mot de passe.
                  </div>
                  <div class="mt-2">
                    <InputText
                        :class="'w-full ' + ((user.email === '') ? 'p-invalid' : '')"
                        id="username"
                        placeholder="Email"
                        type="email"
                        v-model="user.email"
                        @blur="user.email === undefined ? user.email = '' : ''"
                        @keyup.enter="onClick"
                    />
                  </div>
                  <div class="flex items-center flex-col justify-between gap-2 pt-4">
                    <CustomButton label="Continuer" @click="onClick"/>
                  </div>
              </div>
            </template>
        </Card>
    </div>
  </div>
</template>

<script lang="ts">
import { defineComponent, ref, computed } from "vue";
import { useUserStore } from "../store/UserStrore";
import InputText from "primevue/inputtext";
// import Button from "primevue/button";
import userApi from "../api/UserApi"
import User from "../models/User"
import Card from "primevue/card"
import { useToast } from 'primevue/usetoast';
import CustomButton from "../components/CustomButton.vue";

export default defineComponent({
  name: "LoginPage",
  components: {
    InputText,
    // Button,
    Card,
    CustomButton
  },
  setup() {
    const usersStore = useUserStore();
    const user = ref<User>(new User())
    const toast = useToast();

    const appUser = computed(() => {
      return usersStore.getUser;
    });


    const onClick = () => {
      userApi.resetPassword(user.value.email).then(() => {
        toast.add({
          severity: "info",
          summary: "Validé",
          detail: "Un mail vous a été envoyé",
          life: 5000,
        });
      })
    }

    return {
      appUser,
      onClick,
      user
    };
  },
});
</script>

<style scoped>

</style>