<template>
  <div class="flex items-center justify-center h-screen">
    <div class="w-full max-w-md">
        <div class="mb-4">
          <img src="../assets/winApp.png" alt="winApp" class="w-80 mx-auto py-5"/>
        </div>
        <Card class="p-card-no-padding">
            <template #title>
                Changement du mot de passe
            </template>
            <template #content>
                <div class="px-8 pb-7">
                  <div class="text-center">
                    Saisissez votre nouveau mot de passe puis confirmer le.
                  </div>
                  <div class="mt-2">
                    <InputText
                        :class="'w-full ' + ((user.newPassword === '') ? 'p-invalid' : '')"
                        placeholder="Nouveau mot de passe"
                        type="password"
                        v-model="user.newPassword"
                        @blur="user.newPassword === undefined ? user.newPassword = '' : ''"
                        @keyup.enter="onClick"
                    />
                  </div>
                  <div class="mt-2">
                    <InputText
                        :class="'w-full ' + ((confirmPassword === '') ? 'p-invalid' : '')"
                        placeholder="Confirmer le mot de passe"
                        type="password"
                        v-model="confirmPassword"
                        @blur="confirmPassword === undefined ? confirmPassword = '' : ''"
                        @keyup.enter="onClick"
                    />
                  </div>
                  <div class="flex items-center flex-col justify-between gap-2 pt-4">
                    <CustomButton label="Modifier" icon="pi pi-check" @click="onClick" :disabled="!isSame"/>
                  </div>
              </div>
            </template>
        </Card>
    </div>
  </div>
</template>

<script lang="ts">
import { defineComponent, ref, computed, onMounted } from "vue";
import { useUserStore } from "../store/UserStrore";
import InputText from "primevue/inputtext";
import User from "../models/User"
import Card from "primevue/card"
import { useToast } from 'primevue/usetoast';
import CustomButton from "../components/CustomButton.vue";
import { useRoute } from "vue-router";
import UserApi from "../api/UserApi";
import router from "../router";

export default defineComponent({
  name: "newPasswordPage",
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
    const token = ref<string>()
    const route = useRoute();
    const confirmPassword = ref<string>()

    const appUser = computed(() => {
      return usersStore.getUser;
    });

    onMounted(() => {
      token.value = route.query.token as string;
      user.value.token = token.value;
    })


    const onClick = () => {
      UserApi.changePassword(user.value).then(() => {
        toast.add({
          severity: "success",
          summary: "Enregistré",
          detail: "Le mot de passe à bien été changé",
          life: 5000,
        });
        router.push({name: "LoginPage"})
      })
    }

    const isSame = computed(() => {
      return user.value["newPassword"] === confirmPassword.value
    })

    return {
      appUser,
      onClick,
      user,
      confirmPassword,
      isSame
    };
  },
});
</script>

<style scoped>

</style>