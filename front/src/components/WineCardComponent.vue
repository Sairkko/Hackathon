<script setup>
import { defineProps, ref } from 'vue';

const props = defineProps({
  title: String,
  type: String,
  millesime: Number,
  cepage: String,
  note: String,
  stock: Number,
  image: String
});

const visible = ref(false);
const isEdit = ref(null)

const wine_name = ref('');
const wine_age = ref('');
const wine_type = ref('');
const wine_cepage = ref('');
const wine_note = ref('');
const wine_stock = ref(0);

function prepareForModification() {
  wine_name.value = props.title;
  wine_age.value = props.millesime.toString();
  wine_type.value = props.type;
  wine_cepage.value = props.cepage;
  wine_note.value = props.note;
  wine_stock.value = props.stock;
  isEdit.value = true;
  visible.value = true;
}

function prepareForDeletion(){
  isEdit.value = false;
  visible.value = true;
}
</script>

<template>
  <Card class="my-3 w-[547px]">
    <template #content>
      <div class="grid grid-cols-4">
        <div class="rounded-l-lg col-span-1 bg-[url('../assets/atelier.png')] bg-cover"></div>
        <div class="col-span-3 text-left p-4">
          <ul>
            <li class="font-semibold">{{ props.title }} ({{ props.millesime }})</li>
            <li>{{ props.type }}</li>
            <li>{{ props.cepage }}</li>
            <li class="font-light italic text-grey">"{{ props.note }}"</li>
          </ul>
          <div class="flex justify-between mt-3">
            <p  class="font-semibold my-auto">En Stock: {{ props.stock }}</p>
            <div class="flex justify-end mr-2">
              <a @click="prepareForModification" class="bg-[#2A8DA3] text-white px-3 pb-1 pt-2 mx-1 rounded-md cursor-pointer">Modifier</a>
              <a @click="prepareForDeletion" class="bg-[#E04242] text-white px-3 pb-1 pt-2 mx-1 rounded-md cursor-pointer">Supprimer</a>
            </div>
          </div>
        </div>
      </div>
    </template>
  </Card>

  <Dialog v-if=isEdit v-model:visible="visible" modal header="Modification du vin" class="w-1/3">
    <div class="flex flex-col gap-2">
      <label class="text-sm font-semibold" for="wine_name">Nom du vin</label>
      <InputText id="wine_name" v-model="wine_name" />
    </div>
    <div class="flex flex-col gap-2">
      <label class="text-sm font-semibold" for="wine_age">Milésime</label>
      <InputText id="wine_age" v-model="wine_age" />
    </div>
    <div class="flex flex-col gap-2">
      <label class="text-sm font-semibold" for="wine_type">Type de vin</label>
      <InputText id="wine_type" v-model="wine_type" />
    </div>
    <div class="flex flex-col gap-2">
      <label class="text-sm font-semibold" for="wine_cepage">Cépages</label>
      <InputText id="wine_cepage" v-model="wine_cepage" />
    </div>
    <div class="flex flex-col gap-2">
      <label class="text-sm font-semibold" for="wine_stock">Stock</label>
      <InputText id="wine_stock" v-model="wine_stock" />
    </div>
    <div class="flex flex-col gap-2">
      <label class="text-sm font-semibold" for="wine_note">Note</label>
      <Textarea class="bg-white" v-model="wine_note" variant="filled" rows="5" cols="30" />
    </div>

    <div class="flex justify-content-end gap-2">
      <a @click="visible=false" class="bg-[#309A1F] text-center text-white px-3 pb-1 pt-2 mt-3 rounded-md w-full cursor-pointer">Valider</a>
    </div>
  </Dialog>
  <Dialog v-else v-model:visible="visible" modal header="Supression du vin" class="w-1/3">
    <h1 class="m-8">Êtes-vous sûr de vouloir supprimer le vin "{{ props.title }}" ?</h1>
    <div class="flex justify-center gap-3">
      <a @click="visible=false" class="bg-grey text-center text-white px-3 pb-1 pt-2 mt-3 rounded-md cursor-pointer max-w-max">Annuler</a>
      <a @click="visible=false" class="bg-[#E04242] text-center text-white px-3 pb-1 pt-2 mt-3 rounded-md cursor-pointer max-w-max">Confirmer la suppression</a>
    </div>
  </Dialog>
</template>

