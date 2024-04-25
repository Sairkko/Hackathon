<script setup>
import {defineProps, defineEmits, ref} from 'vue';
import ProductApi from "@/api/ProductApi";

const props = defineProps({
  data: Object,
});

console.log(props.data)

const emit = defineEmits(["deleteOne", "updateOne"])

const visible = ref(false);
const isEdit = ref(null)

const wine_nom = ref('');
const wine_age = ref('');
const wine_type = ref('');
const wine_cepage = ref('');
const wine_description = ref('');
const wine_stock = ref(0);
const wine_region = ref('');
const wine_volume = ref(0);

function prepareForModification() {
  wine_nom.value = props.data.nom;
  wine_age.value = props.data.millesime.toString();
  wine_type.value = props.data.type;
  wine_cepage.value = props.data.cepage;
  wine_description.value = props.data.description;
  wine_stock.value = props.data.stock;
  wine_region.value = props.data.region
  wine_volume.value = props.data.volume

  isEdit.value = true;
  visible.value = true;
}

function prepareForDeletion() {
  isEdit.value = false;
  visible.value = true;
}

async function deleteProduct() {
  const requ = await ProductApi.removeProduct(props.data.id);
  console.log(requ);
  visible.value = false;
  emit("deleteOne", props.data.id)
}

async function editProduct() {
  const newData = {
    nom: wine_nom.value,
    millesime: wine_age.value,
    region: wine_region.value,
    cepage: wine_cepage.value,
    type: wine_type.value,
    volume: wine_volume.value,
    stock: wine_stock.value,
    description: wine_description.value,
  };

  const requ = await ProductApi.editProduct(props.data.id, newData);
  console.log(".................");
  console.log(requ);
  console.log(newData);
  visible.value = false;
  emit("updateOne", newData, props.data.id)
}

</script>

<template>
  <Card class="my-3 w-[547px]">
    <template #content>
      <div class="grid grid-cols-4">
        <div class="rounded-l-lg col-span-1 bg-[url('../assets/atelier.png')] bg-cover"></div>
        <div class="col-span-3 text-left p-4">
          <ul>
            <li class="font-semibold">{{ props.data.nom }} ({{ props.data.millesime }}) - {{ props.data.volume }}</li>
            <li class="font-semibold"></li>
            <li>Provenance: {{ props.data.region }}</li>
            <li>{{ props.data.type }}</li>
            <li>{{ props.data.cepage }}</li>
            <li class="font-light italic text-grey">"{{ props.data.description }}"</li>
          </ul>
          <div class="flex justify-between mt-3">
            <p class="font-semibold my-auto">En Stock: {{ props.data.stock }}</p>
            <div class="flex justify-end mr-2">
              <a @click="prepareForModification"
                 class="bg-[#2A8DA3] text-white px-3 pb-1 pt-2 mx-1 rounded-md cursor-pointer">Modifier</a>
              <a @click="prepareForDeletion"
                 class="bg-[#E04242] text-white px-3 pb-1 pt-2 mx-1 rounded-md cursor-pointer">Supprimer</a>
            </div>
          </div>
        </div>
      </div>
    </template>
  </Card>

  <Dialog v-if=isEdit v-model:visible="visible" modal header="Modification du vin" class="w-1/3">
    <div class="flex flex-col gap-2">
      <label class="text-sm font-semibold" for="wine_nom">Nom du vin</label>
      <InputText id="wine_nom" v-model="wine_nom"/>
    </div>
    <div class="flex flex-col gap-2">
      <label class="text-sm font-semibold" for="wine_age">Milésime</label>
      <InputText id="wine_age" v-model="wine_age"/>
    </div>
    <div class="flex flex-col gap-2">
      <label class="text-sm font-semibold" for="wine_volume">Volume</label>
      <InputText id="wine_volume" v-model="wine_volume"/>
    </div>
    <div class="flex flex-col gap-2">
      <label class="text-sm font-semibold" for="wine_region">Région de provenance</label>
      <InputText id="wine_type" v-model="wine_region"/>
    </div>
    <div class="flex flex-col gap-2">
      <label class="text-sm font-semibold" for="wine_type">Type de vin</label>
      <InputText id="wine_type" v-model="wine_type"/>
    </div>
    <div class="flex flex-col gap-2">
      <label class="text-sm font-semibold" for="wine_cepage">Cépages</label>
      <InputText id="wine_cepage" v-model="wine_cepage"/>
    </div>
    <div class="flex flex-col gap-2">
      <label class="text-sm font-semibold" for="wine_stock">Stock</label>
      <InputText id="wine_stock" v-model="wine_stock"/>
    </div>
    <div class="flex flex-col gap-2">
      <label class="text-sm font-semibold" for="wine_description">Note</label>
      <Textarea class="bg-white" v-model="wine_description" variant="filled" rows="5" cols="30"/>
    </div>
    <div class="flex justify-content-end gap-2">
      <a @click="editProduct"
         class="bg-[#309A1F] text-center text-white px-3 pb-1 pt-2 mt-3 rounded-md w-full cursor-pointer">Valider</a>
    </div>
  </Dialog>
  <Dialog v-else v-model:visible="visible" modal header="Supression du vin" class="w-1/3">
    <h1 class="m-8">Êtes-vous sûr de vouloir supprimer le vin "{{ props.title }}" ?</h1>
    <div class="flex justify-center gap-3">
      <a @click="visible=false"
         class="bg-grey text-center text-white px-3 pb-1 pt-2 mt-3 rounded-md cursor-pointer max-w-max">Annuler</a>
      <a @click="deleteProduct"
         class="bg-[#E04242] text-center text-white px-3 pb-1 pt-2 mt-3 rounded-md cursor-pointer max-w-max">Confirmer
        la suppression</a>
    </div>
  </Dialog>
</template>

