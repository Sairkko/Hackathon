<template>
  <div class="flex items-center justify-center h-screen">
    <div class="w-full max-w-md">
        <div class="mb-4">
          <img src="../assets/winApp.png" alt="winApp" class="w-80 mx-auto py-5"/>
        </div>
        <Card class="p-card-no-padding">
            <template #title>
                Connexion
            </template>
            <template #content>
                <div class="px-8">
                    <InputText
                        :class="'w-full ' + ((user.email === '') ? 'p-invalid' : '')"
                        id="username"
                        placeholder="Email"
                        type="email"
                        v-model="user.email"
                        @blur="user.email === undefined ? user.email = '' : ''"
                        @keyup.enter="onClick"
                    />
                    <InputText
                        :class="'w-full ' + ((user.password === '') ? 'p-invalid' : '')"
                        id="password"
                        placeholder="Password"
                        type="password"
                        v-model="user.password"
                        @blur="user.password === undefined ? user.password = '' : ''"
                        @keyup.enter="onClick"
                    />
                    <div class="flex items-center flex-col justify-between gap-2 pt-4">
                        <!-- <router-link :to="{name: 'ResetPasswordPage'}">
                          <span class="inline-block align-baseline font-bold text-sm text-red-500 hover:text-red-800">
                            Mot de passe oublié?
                          </span>
                        </router-link> -->
                        <CustomButton label="Valider" @click="onClick"/>
                        
                        <span>
                            Vous n'avez pas de compte ?
                        </span>
                        <router-link :to="{name: 'RegisterPage'}">
                            <span class="inline-block align-baseline font-bold text-sm text-red-500 hover:text-red-800 pb-4">
                              Créez votre compte
                            </span>
                        </router-link>
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
import router from "../router";
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
      userApi.login(user.value).then(response => {
        if(response.data.statusCode !== 200){
            toast.add({
            severity:'error',
            summary: response.data.message,
            life: 3000});
        }else{
          const el = Object.assign(new User(),response.data.data)
          usersStore.setUser(el)
          localStorage.setItem("user", JSON.stringify(response.data.data))
          router.push({path: "/"})
        }
        
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