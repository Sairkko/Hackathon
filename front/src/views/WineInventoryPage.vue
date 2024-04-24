<script setup>
import WineCardComponent from "@/components/WineCardComponent.vue";
</script>

<template>
  <main class="mt-5 mx-28">
    <h1 class="text-xl mb-5">Inventaire des vins</h1>
    <div class="flex flex-wrap gap-x-5">
      <div v-for="i in 50" :key="i">
        <WineCardComponent
            title="Tavel 75cl du Rhone"
            type="Vin rouge"
            :millesime=2004
            cepage="le cépage"
            note="Fin et sucré en bouche"
            :stock=25
            image="../assets/winApp.png"
        />
      </div>
    </div>

  </main>
</template>