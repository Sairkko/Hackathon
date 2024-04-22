<template>
  <div class="login-body">
    <div class="body-container">
      <div class="grid">
        <div class="col-12 lg-4 text-center">
          <img
            src="../assets/winApp.png"
            alt="winApp"
            style="width: 400px; padding: 20px;"
          />
          <h1 class="login-title">Connexion</h1>
          
        </div>
        <div class="col-12 lg-6 right-side">
          <div class="login-wrapper">
            <div class="login-container">
              <span class="title">Login</span>

              <div class="grid fluid">
                <div class="col-12">
                  <InputText
                    id="username"
                    placeholder="Username"
                    type="email"
                    v-model="username"
                    @keyup.enter="onClick"
                  />
                </div>
                <div class="col-12">
                  <InputText
                    id="password"
                    placeholder="Password"
                    type="password"
                    v-model="password"
                    @keyup.enter="onClick"
                  />
                </div>
                <div class="col-6">
                  <Button class="button-plug " label="Sign In" icon="pi pi-check" @click="onClick" />
                </div>
                <div class="col-6 password-container">
                  <button @click="resetPassword" class="link">
                    Forgot Password?
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script lang="ts">
import { defineComponent, ref, computed } from "vue";
import { useUserStore } from "../store/UserStrore";
import InputText from "primevue/inputtext";
import Button from "primevue/button";

export default defineComponent({
  name: "LoginPage",
  components: {
    InputText,
    Button,
  },
  setup() {
    const username = ref("");
    const password = ref("");
    const usersStore = useUserStore();

    const appUser = computed(() => {
      return usersStore.getUser;
    });


    const onClick = () => {
    //   login user
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
      username,
      password,
      onClick,
      resetPassword,
    };
  },
});
</script>

<style scoped>
.login-title {
  font-size:30px;
  padding: 20px;
}
.button.button-plug, .buttonset.button-plug > .button, .splitbutton.button-plug > .button {
    color: #ffffff;
    background: linear-gradient(#2b59a5, #2d89c9);
    border: 1px solid #2b59a5;
}
</style>