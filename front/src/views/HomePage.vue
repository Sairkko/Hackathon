<template>
  <div id="home_page">
    <section class="container mx-auto">
      <div class="flex flex-col md:flex-row items-center py-4">
        <div class="md:flex-1">
          <img src="../assets/wine-splash.png" alt="Verre de vin" class="w-full h-auto">
        </div>
        <div class="md:flex-1 text-center md:text-left p-4">
          <h2 class="text-2xl font-bold mb-4 text-red">Explorez le Monde des Vins</h2>
          <p class="mb-4">Découvrez, apprenez et savourez une sélection exquise de vins du monde entier, guidé par un passionné.</p>
          <a href="#" class="bg-red hover:bg-hover-red text-white py-2 px-4 rounded">
            Découvrir
          </a>
        </div>
      </div>
    </section>

    <section class="py-16 bg-lite-grey">
      <div class="container mx-auto px-4">
        <h2 class="text-3xl font-semibold mb-6"> <span class="text-red">Découverte et dégustation des Vins,</span> un voyage à travers les saveurs</h2>
        <p class="mb-4">Rejoignez-nous pour un atelier exceptionnel de dégustation de vins dans un cadre historique et enchanteur. Au cours de cet événement de deux heures trente, vous serez guidé par les principes de la prestigieuse formation Wine and Spirit Education Trust (WSET).</p>
        <p class="mb-8">Découvrez les secrets des vins de diverses régions, que ce soit les nuances délicates des blancs, la profondeur des rouges, ou l'unicité des spécialités régionales.</p>
      </div>
    </section>

    <section class="py-16 bg-gray-200">
      <div class="container mx-auto px-20">
        <h3 class="text-2xl font-semibold mb-8">Nos ateliers de dégustation</h3>
        <div class="flex flex-wrap -mx-4">
          <div class="md:w-1/2 px-4 mb-8">
            <div class="relative">
              <img src="../assets/exploration-terrestre.png" alt="Exploration des vins" class="w-full h-auto">
              <div class="absolute bottom-0 flex flex-col justify-center items-center bg-black bg-opacity-50 p-4">
                <div class="text-center bg-white rounded p-4">
                  <h4 class="text-xl font-bold text-dark-red"><span class="text-red">Exploration Terrestre : </span>Plongée dans les Saveurs Vinicoles</h4>
                  <p class="my-4 hidden lg:block">Explorez les secrets des vins régionaux, des blancs subtils aux rouges profonds, en passant par les spécialités uniques.</p>
                  <a href="#" class="bg-red hover:bg-hover-red text-white py-2 px-4 rounded">
                    Réserver
                  </a>
                </div>
              </div>
            </div>
          </div>
          <div class="md:w-1/2 px-4 mb-8">
            <div class="relative">
              <img src="../assets/exploration-celeste.png" alt="Exploration des vins" class="w-full h-auto">
              <div class="absolute bottom-0 flex flex-col justify-center items-center bg-black bg-opacity-50 p-4">
                <div class="text-center bg-white rounded p-4">
                  <h4 class="text-xl font-bold text-dark-red"><span class="text-red">Exploration Céleste: </span>Astrum Vitis, Alchimie des Terroirs et des Étoiles</h4>
                  <p class="my-4 hidden lg:block">Sous l'égide du Vin et des Étoiles, plongez dans un Voyage Vinicole au Cœur du Cosmos.</p>
                  <a href="#" class="bg-red hover:bg-hover-red text-white py-2 px-4 rounded">
                    Réserver
                  </a>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>
</template>

<script>

export default {
}
</script>

<style>

</style>