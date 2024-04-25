<template>
  <div class="relative min-h-screen bg-no-repeat bg-cover bg-fixed">
    <img src="@/assets/Fond.png" alt="Background" class="w-full object-cover" style="height: 470px;"/>
    <div class="absolute inset-0 flex items-center justify-center" style="height: 470px;">
      <div class="text-center text-white">
        <h1 class="text-3xl md:text-4xl font-bold">Les Trésors du Rhône. Un Parcours Épicurien</h1>
        <p class="text-sm md:text-lg mt-2">Une expérience unique dédiée à la découverte des vins de la Vallée du Rhône.</p>
      </div>
    </div>
    <section class="px-10 pt-24 pb-24 bg-black">
      <div class="grid lg:grid-cols-3 items-center justify-items-center text-left">
        <div class="shadow-2xl lg:col-span-1">
          <img src="@/assets/imageVin.png" alt="Image du Vin" class="mx-auto">
        </div>
        
          <div class="md:flex md:space-x-8 lg:col-span-2">
            <div class="md:flex-1">
              <p> Cet atelier est conçu spécialement pour ceux qui débutent dans le monde fascinant de la dégustation du vin, offrant une introduction parfaite aux trésors cachés de cette région réputée.</p>
              <p> Au cours de cet atelier, vous apprendrez à identifier les caractéristiques uniques des vins du Rhône, à travers leurs arômes, leurs saveurs, et leurs textures.</p>
            </div>
            <div class="md:flex-1 border-l-2 border-lite-grey pl-4">

              <h2 class="text-xl font-semibold mb-4">Prochaines sessions</h2>
              <ul class="list-none pl-5 mb-4">
                <li class="text-red ">
                  <font-awesome-icon :icon="['fas', 'calendar-days']" />
                    <span class="text-dark-red pl-2"> 22/08/2025 à 18h00</span> 
                  </li>
                <li class="text-red">
                  <font-awesome-icon :icon="['fas', 'location-dot']" />
                  <span class="text-dark-red pl-2"> Quelque part loin de tout, Lyon, France </span>
                </li>
                <li class="mt-2 mb-5"><Button label="Participer"  class="bg-red px-2 py-0.5" /></li>
              </ul>
              <ul class="list-none pl-5 mb-4">
                <li class="text-red ">
                  <font-awesome-icon :icon="['fas', 'calendar-days']" />
                    <span class="text-dark-red pl-2"> 22/08/2025 à 18h00</span> 
                  </li>
                <li class="text-red">
                  <font-awesome-icon :icon="['fas', 'location-dot']" />
                  <span class="text-dark-red pl-2"> Quelque part loin de tout, Lyon, France</span>
                </li>
              </ul>
              
            </div>
          </div>
      </div>
    </section>
    

  </div>
</template>

<script>
import Button from 'primevue/button';

export default {
  name: 'WorkshopDetailsPages',
  components: {
    Button
  },
  props: ['id']
};
</script>

<style scoped>
</style>
