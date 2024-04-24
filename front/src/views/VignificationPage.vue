<script setup lang="ts">
import { ref } from 'vue';

import Accordion from 'primevue/accordion';
import AccordionTab from 'primevue/accordiontab';


const activeIndex = ref(null);
</script>

<template>
  <div class="bg-white text-gray-800">
    <section class="px-4 py-16">
      <h1 class="text-3xl font-bold text-center text-dark-red">La vinification, qu'est-ce que c'est ?</h1>
      <p class="mt-12 mx-16 text-justify font-bold">
        La vinification est l'opération qui permet de transformer le raisin en  vin. Il faut environ 1,3 à 1,5 kg de raisin pour obtenir un litre de  vin. Pour chaque type de vin, rouge, blanc, rosé ou effervescent,  différentes étapes permettent de produire le vin. Les principales  opérations, qui s’enchainent différemment selon le type de vin, sont  notamment : l’égrappage, le pressurage, la macération et la  fermentation.
      </p>
    </section>
    <Accordion v-model:activeIndex="activeIndex" multiple class="mx-16 pb-8">
      <AccordionTab header="Élaboration des Vins Rouges">
        <section class="px-4 py-8">
          <p class="mt-8 mx-16 text-justify font-bold">
            Le principe majeur de cette vinification est le contact plus ou moins prolongé entre la pellicule (peau) du raisin et le moût (jus).
          </p>
          <div class="flex flex-col items-center">
            <img src="../assets/vini1.png" alt="Élaboration des Vins Rouges" class="h-img" />
          </div>
        </section>
      </AccordionTab>

      <AccordionTab header="Élaboration des Vins Blancs">
        <section class="px-4 py-8">
          <p class="mt-8 mx-16 text-justify font-bold">
            Le principe majeur de cette vinification est le contact plus ou moins prolongé entre la pellicule (peau) du raisin et le moût (jus).
          </p>
          <div class="flex flex-col items-center">
            <img src="../assets/vini2.png" alt="Élaboration des Vins Blancs" class="h-img" />
          </div>
        </section>
      </AccordionTab>

      <AccordionTab header="Élaboration des Vins Effervescents">
        <section class="px-4 py-8">
          <p class="mt-8 mx-16 text-justify font-bold">
            Le principe majeur de cette vinification est le contact plus ou moins prolongé entre la pellicule (peau) du raisin et le moût (jus).
          </p>
          <div class="flex flex-col items-center">
            <img src="../assets/vini3.png" alt="Élaboration des Vins Effervescentse" class="h-img" />
          </div>
        </section>
      </AccordionTab>

      <AccordionTab header="Élaboration des Vins Rosés">
        <section class="px-4 py-8">
          <p class="mt-8 mx-16 text-justify font-bold">
            Le principe majeur de cette vinification est le contact plus ou moins prolongé entre la pellicule (peau) du raisin et le moût (jus).
          </p>
          <div class="flex flex-col items-center">
            <img src="../assets/vini4.png" alt="Élaboration des Vins Rosés" class="h-img" />
          </div>
        </section>
      </AccordionTab>

    </Accordion>
  </div>
</template>

<style scoped>
.h-img {
  height: 22rem;
}

@media screen and (min-width: 568px) {
  .h-img {
    height: 40rem;
  }
}
</style>
