<template>
  <AppLayout/>
</template>

<script lang="ts">
import { Options, Vue } from 'vue-class-component';
import AppLayout from './layouts/AppLayout.vue';

@Options({
  components: {
    AppLayout,
  },
})
export default class App extends Vue {}
</script>

<style>
#app {
  font-family: Avenir, Helvetica, Arial, sans-serif;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
  text-align: center;
  @apply text-dark-red;
}
</style>
