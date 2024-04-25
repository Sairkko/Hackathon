<script setup>
import WineCardComponent from "@/components/WineCardComponent.vue";
import {onMounted, ref} from "vue";
import AtelierApi from "@/api/AtelierApi";

const selectedFilter = ref();
const options = ref([
  { name: 'Vins rouge', code: 'VR' },
  { name: 'Vins blancs', code: 'VB' },
  { name: 'Stock croissant', code: 'SC' },
  { name: 'Stock décroissant', code: 'SD' },
]);

const visible = ref(false);

const new_wine_name = ref('');
const new_wine_age = ref('');
const new_wine_type = ref('');
const new_wine_cepage = ref('');
const new_wine_note = ref('');
const new_wine_stock = ref('');

const allWine = ref([]);

onMounted(async () => {
  const requ = await AtelierApi.product();
  allWine.value = requ.data.data;
  console.log(allWine)
});



</script>

<template>
  <h1 class="text-2xl my-8">Inventaire des vins</h1>
  <main class="my-8 mx-28">
    <div class="flex items-center justify-between">
      <div class="flex gap-8 bg-[#F3F2F2] p-2 w-min">
        <div class="flex gap-2 items-center mx-2 justify-center">
          <label class="text-sm">Affichage</label>
          <Dropdown v-model="selectedFilter" :options="options" optionLabel="name" placeholder="Sélectionner un affichage" class="w-full md:w-[14rem]" />
        </div>
        <InputGroup class="flex gap-1">
          <InputText class="pl-3" placeholder="Rechercher un dossier" />
          <a class="bg-[#c10041] text-white px-3 pb-1 pt-2 mx-1 rounded-md cursor-pointer">Rechercher</a>
        </InputGroup>
      </div>
      <a @click="visible=true" class="border border-[#c10041] text-[#c10041] px-3 pb-1 pt-2 mx-1 rounded-md cursor-pointer">+ Ajouter un vin</a>
    </div>
    <div class="flex flex-wrap gap-x-5">
      <div v-for="item in allWine" :key="item">
        <WineCardComponent
            :id=item.id
            :title= "`${item.name}, ${item.region}`"
            :type="item.type"
            :millesime=item.millesime
            :cepage=item.cepage
            :note=item.description
            :stock=item.stock
            image="../assets/winApp.png"
        />
      </div>
    </div>
  </main>

  <Dialog v-model:visible="visible" modal header="Ajout d'un nouveau vin" class="w-1/3">
    <div class="flex flex-col gap-2">
      <label class="text-sm font-semibold" for="new_wine_name">Nom du vin</label>
      <InputText id="new_wine_name" v-model="new_wine_name" placeholder="Nom du vin"/>
    </div>
    <div class="flex flex-col gap-2">
      <label class="text-sm font-semibold" for="new_wine_age">Milésime</label>
      <InputText id="new_wine_age" v-model="new_wine_age" placeholder="Milésime"/>
    </div>
    <div class="flex flex-col gap-2">
      <label class="text-sm font-semibold" for="new_wine_type">Type de vin</label>
      <InputText id="new_wine_type" v-model="new_wine_type" placeholder="Vin rouge"/>
    </div>
    <div class="flex flex-col gap-2">
      <label class="text-sm font-semibold" for="new_wine_cepage">Cépages</label>
      <InputText id="new_wine_cepage" v-model="new_wine_cepage" placeholder="Cépage"/>
    </div>
    <div class="flex flex-col gap-2">
      <label class="text-sm font-semibold" for="new_wine_stock">Stock</label>
      <InputText id="new_wine_stock" v-model="new_wine_stock" placeholder="300"/>
    </div>
    <div class="flex flex-col gap-2">
      <label class="text-sm font-semibold" for="new_wine_note">Note</label>
      <Textarea class="bg-white" v-model="new_wine_note" variant="filled" rows="5" cols="30" placeholder="Une petite notation du vin..."/>
    </div>

    <div class="flex justify-content-end gap-2">
      <a @click="visible=false" class="bg-[#309A1F] text-center text-white px-3 pb-1 pt-2 mt-3 rounded-md w-full cursor-pointer">Valider</a>
    </div>
  </Dialog>
</template>