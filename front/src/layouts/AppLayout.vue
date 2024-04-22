<template>
  <div id="app_layout">
    <Toast position="top-right" />
    <router-view :key="$route.fullPath">
    </router-view>
  </div>
</template>

<script lang="ts">
import { defineComponent } from "vue";
// import router from "../router";
import { useUserStore } from '../store/UserStrore';


export default defineComponent({
  name: "AppLayout",
  setup() {
    const userStore = useUserStore();
    const user = userStore.getUser!

    //  router.beforeEach((to, from, next) => {
    //   if (to.name != 'Login' && user.token == "" ) {
    //     next({ name: 'Login' });
    //   } 
    //   else {
    //     const permissions: string = typeof to.meta.permissions == 'string' ? to.meta.permissions : '';
    //     if (permissions != '' && !usersStore.can(permissions)) {
    //       next({ name: 'Forbidden403' });
    //     } else {
    //       next();
    //     }
    //   }
    // })

    if (user && user.token == "" && localStorage.getItem("user")) {
      userStore.setUser(JSON.parse(localStorage.getItem("appUser")!))
    }
    

    return {
    }
  }
})
</script>

<style scoped>

</style>