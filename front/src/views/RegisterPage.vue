<template>
  <div class="flex items-center justify-center h-screen">
    <div class="w-full max-w-md">
        <div class="mb-4">
          <img src="../assets/winApp.png" alt="winApp" class="w-80 mx-auto py-5"/>
        </div>
        <Card class="p-card-no-padding">
            <template #title>
                Inscription
                
            </template>
            <template #content>
                <div class="px-8">
                  <div class="mt-2">
                    <InputText
                        :class="'w-full ' + ((user.firstName === '') ? 'p-invalid' : '')"
                        id="firstName"
                        placeholder="Nom *"
                        type="firstName"
                        v-model="user.firstName"
                        @keyup.enter="onClick"
                        @blur="user.firstName === undefined ? user.firstName = '' : ''"
                    />
                </div>
                <div class="mt-2">
                    <InputText
                        :class="'w-full ' + ((user.lastName === '') ? 'p-invalid' : '')"
                        id="lastName"
                        placeholder="Prénom *"
                        type="lastName"
                        v-model="user.lastName"
                        @keyup.enter="onClick"
                        @blur="user.lastName === undefined ? user.lastName = '' : ''"
                    />
                </div>
                <div class="mt-2">
                    <InputText
                        class="w-full"
                        id="phoneNumber"
                        placeholder="Téléphone"
                        type="phoneNumber"
                        v-model="user.phoneNumber"
                        @keyup.enter="onClick"
                    />
                </div>
                <div class="mt-2">
                    <InputText
                        :class="'w-full ' + ((user.email === '') ? 'p-invalid' : '')"
                        id="email"
                        placeholder="Email *"
                        type="email"
                        v-model="user.email"
                        @keyup.enter="onClick"
                        @blur="user.email === undefined ? user.email = '' : ''"
                    />
                </div>
                <div class="mt-2">
                    <InputText
                        :class="'w-full ' + ((user.password === '') ? 'p-invalid' : '')"
                        id="password"
                        placeholder="Password *"
                        type="password"
                        v-model="user.password"
                        @keyup.enter="onClick"
                        @blur="user.password === undefined ? user.password = '' : ''"
                    />
                </div>
                    <div class="flex items-center flex-col justify-between gap-2">
                        <CustomButton label="Valider" @click="onClick"/>
                        <span>
                            Vous avez un compte ?
                        </span>
                        <router-link :to="{path: '/login'}">
                          <a
                              class="inline-block align-baseline font-bold text-sm text-red-500 hover:text-red-800 pb-4"
                              href="#"
                          >
                              Connectez vous !
                          </a>
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
import router from "../router/index";
import CustomButton from "../components/CustomButton.vue";

export default defineComponent({
  name: "RegisterPage",
  components: {
    InputText,
    // Button,
    Card,
    CustomButton
  },
  setup() {
    const usersStore = useUserStore();
    const user = ref<User>(new User())
    

    const appUser = computed(() => {
      return usersStore.getUser;
    });


    const onClick = () => {
     userApi.register(user.value).then(response => {
        console.log(response)
        router.push({path: '/login'})
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