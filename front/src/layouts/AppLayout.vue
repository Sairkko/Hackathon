<template>
  <div id="app_layout">
    <Toast position="top-right" />
    <component :is="layout">
      <router-view>
      </router-view>
    </component>
  </div>
</template>

<script lang="ts">
import { computed, defineComponent, shallowRef, watch} from "vue";
import { useRoute } from "vue-router";
import User from "../models/User";
import { useUserStore } from '../store/UserStrore';
import BaseLayout from "./BaseLayout.vue";
import PageLayout from "./PageLayout.vue";


export default defineComponent({
  name: "AppLayout",
  setup() {
    const usersStore = useUserStore();
    const user = computed(() => {
      return usersStore.getUser;
    });
    const layout = shallowRef(PageLayout);
    const route = useRoute();

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

    if (!user.value && localStorage.getItem("user")) {
      usersStore.setUser(Object.assign(new User(),JSON.parse(localStorage.getItem("user")!)))
    }
    
    watch(
      () => route.meta,
        (meta) => {
        switch (meta.layout) {
          case "PageLayout":
            layout.value = PageLayout;
            break;
          case "BaseLayout":
            layout.value = BaseLayout;
            break;
        }
      },
      { immediate: false }
    );
    
    return { layout };
  }
})
</script>

<style scoped>

</style>