<template>
    <h1 class="text-3xl font-bold text-center text-dark-red my-10">Gestion de la page a propos</h1>
    <div class="max-w-4xl mx-auto p-5">
        <!-- Grid container -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

            <div class="md:col-span-1">
                <div class="mb-4">
                    <label for="category-title" class="block text-sm font-medium text-gray-700">Titre de la page</label>
                    <input type="text" id="category-title" v-model="content.title" class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" placeholder="Entrez un titre">
                </div>

                <div class="mb-4">
                    <label for="featured-part" class="block text-sm font-medium text-gray-700">Partie mise en valeur du titre</label>
                    <input type="text" id="featured-part" v-model="content.span" class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" placeholder="Entrez une valeur">
                </div>
            </div>

            <div class="md:col-span-2">
                <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                <textarea id="description" rows="15" v-model="content.description" class="mt-1 block w-full px-3 py-2 bg-white border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" placeholder="Entrez une description"></textarea>
            </div>
        </div>

        <div class="flex justify-end md:justify-end">
            <button @click="editContent" type="submit" class="bg-red mt-4 w-full md:w-auto px-4 py-2 bg-green-600 text-white font-medium rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-opacity-50">
                Valider la section
            </button>
        </div>
    </div>


</template>

<script setup lang="ts">
import {onMounted, ref} from "vue";
import AboutPageApi from "@/api/AboutPageApi";
import AboutPage from "@/models/AboutPage";
import { useToast } from 'primevue/usetoast';

const content = ref<AboutPage>({});
const toast = useToast();

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

const editContent = async () => {
    try {
        await AboutPageApi.editAboutPage(content.value);
        fetchContent()
        toast.add({severity: 'success', summary: 'Content modifié', detail: 'La page a propos a été modifié avec succès.', life: 3000});
    } catch (error) {
        console.error('Erreur lors de la mise à jour:', error);
    }
};
</script>

<style scoped>
</style>
