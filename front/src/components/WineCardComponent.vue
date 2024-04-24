<script setup>

import { defineProps } from 'vue';

const props = defineProps({
  title: String,
  type: String,
  millesime: Number,
  cepage: String,
  note: String,
  stock: Number,
  image: String
})
</script>

<template>
  <Card class="my-3 w-[547px]">
    <template #content>
        <div class="grid grid-cols-4">
<!--          <div :class="['col-span-1', 'bg-[url(\'' + props.image + '\')]', 'bg-cover']"/>-->
          <div class="col-span-1 bg-[url('../assets/atelier.png')] bg-cover"/>
          <div class="col-span-3 text-left p-2" >
            <ul>
              <li class="font-semibold">{{ props.title}} ({{ props.millesime }})</li>
              <li>{{ props.type }}</li>
              <li>{{ props.cepage }}</li>
              <li class="font-light italic">{{ props.note }}</li>
            </ul>
            <div class="flex justify-between mt-3">
              <p class="font-semibold">En Stock: {{ props.stock }}</p>
              <div class="flex justify-end mr-3">
                <a class="bg-[#2A8DA3] text-white px-3 pb-1 pt-2 mx-2 rounded-md">Modifier</a>
                <a class="bg-[#E04242] text-white px-3 pb-1 pt-2 mx-2 rounded-md">Supprimer</a>
              </div>
            </div>
          </div>
        </div>
    </template>
  </Card>
</template>
