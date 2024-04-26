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
import router from "@/router";


export default defineComponent({
  name: "AppLayout",
  setup() {
    const usersStore = useUserStore();
    const user = computed(() => {
      return usersStore.getUser;
    });
    const layout = shallowRef(PageLayout);
    const route = useRoute();

    router.beforeEach((to, from, next) => {
      const allowedRoles = to.meta.roles! as any;
      if(user.value && allowedRoles.includes(user.value.role!)){
        next();
      }else if(to.meta.roles == "*"){
        next();
      }else{
        router.push({path: "forbidden"});
      }
    });

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