<template>
  <section class="px-4 py-16">
    <h1 class="text-3xl font-bold text-center text-dark-red mb-10">{{content.title}} <span class="text-red">{{content.span}}</span></h1>
  <div class="flex justify-center items-center bg-gray-200">
    <div class="flex max-w-4xl bg-white shadow-md">
      <!-- Container for image and content -->
      <div class="flex flex-row">
        <!-- Image Section -->
        <div class="w-1/2">
          <img src="../assets/Bonneton.jpg" alt="Olivier Bonneton" class="object-fill h-full"/>
        </div>
        <!-- Content Section -->
        <div class="w-1/2 p-4">
          <p class="text-justify">
            {{content.description}}
          </p>
        </div>
      </div>
    </div>
  </div>
  </section>

</template>

<script setup lang="ts">
import {onMounted, ref} from "vue";
import AboutPageApi from "@/api/AboutPageApi";
import AboutPage from "@/models/AboutPage";

const content = ref<AboutPage>({});

onMounted(async () => {
    fetchContent()
})

const fetchContent = () => {
  AboutPageApi.getAboutContent().then(response => {
      content.value = response.data.data;
      console.log(content.value);
  }).catch(error => {
      console.error('Error fetching events:', error);
  });
}
</script>

<style scoped>
</style>
