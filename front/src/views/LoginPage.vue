<template>
  <div class="flex items-center justify-center h-screen">
    <div class="w-full max-w-md">
        <div class="mb-4">
          <img src="../assets/winApp.png" alt="winApp" class="w-80 mx-auto py-5"/>
        </div>
        <Card>
            <template #title>
                Connexion
            </template>
            <template #content>
                <div class="mb-6">
                <InputText
                    id="username"
                    placeholder="Username"
                    type="email"
                    v-model="user.email"
                    @keyup.enter="onClick"
                />
                <InputText
                    id="password"
                    placeholder="Password"
                    type="password"
                    v-model="user.password"
                    @keyup.enter="onClick"
                />
                </div>
                <div class="flex items-center flex-col justify-between">
                    <a
                        class="inline-block align-baseline font-bold text-sm text-red-500 hover:text-red-800"
                        href="#"
                        @click="resetPassword"
                    >
                        Mot de passe oublié?
                    </a>
                    <button
                        class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline"
                        type="button"
                        @click="onClick"
                    >
                        Valider
                    </button>
                    
                </div>
                <div class="text-center mt-4">
                <a
                    class="inline-block align-baseline font-bold text-sm text-red-500 hover:text-red-800"
                    href="#"
                >
                    Vous n'avez pas de compte? Créez votre compte
                </a>
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

export default defineComponent({
  name: "LoginPage",
  components: {
    InputText,
    // Button,
    Card
  },
  setup() {
    const usersStore = useUserStore();
    const user = ref<User>(new User())

    const appUser = computed(() => {
      return usersStore.getUser;
    });


    const onClick = () => {
     userApi.login(user.value).then(response => {
        console.log(response.data)
        const el = Object.assign(new User(),response.data.data)
        usersStore.setUser(el)
        localStorage.setItem("user", JSON.stringify(response.data.data))
     })
    };

    const resetPassword = () => {
    //   reset password

    //   apiClient.users.resetPassword(username.value).then(() => {
    //     toast.add({
    //       severity: "info",
    //       summary: i18n.t("user.resetpassword.toast.summary"),
    //       detail: i18n.t("user.resetpassword.toast.detail"),
    //       life: 5000,
    //     });
    //   });
    };

    return {
      appUser,
      onClick,
      resetPassword,
      user
    };
  },
});
</script>

<style scoped>
</style>