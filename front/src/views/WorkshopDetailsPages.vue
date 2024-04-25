<template>
  <div class="relative min-h-screen bg-no-repeat bg-cover bg-fixed">
    <img src="@/assets/Fond.png" alt="Background" class="w-full object-cover" style="height: 470px;"/>
    <div class="absolute inset-0 flex items-center justify-center" style="height: 470px;">
      <div class="text-center text-white">
        <h1 class="text-3xl md:text-4xl font-bold">Les Trésors du Rhône. Un Parcours Épicurien</h1>
        <p class="text-sm md:text-lg mt-2">Une expérience unique dédiée à la découverte des vins de la Vallée du Rhône.</p>
      </div>
    </div>

    <section class="px-10 pt-32  bg-black">

  <div class="grid lg:grid-cols-2 items-center justify-items-center gap-5">
    <div class="order-1 lg:order-1 shadow-2xl ">
      <img src="@/assets/imageVin.png" >
    </div>
    <div class="order-2 lg:order-2  flex flex-col justify-center text-center  ">
      <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
          <p> Cet atelier est conçu spécialement pour ceux qui débutent dans le monde fascinant de la dégustation du vin, offrant une introduction parfaite aux trésors cachés de cette région réputée. La Vallée du Rhône, berceau de vins d'exception, est célèbre pour sa diversité viticole, offrant une gamme de vins rouges et blancs qui séduisent les palais du monde entier. </p>
          <p> Au cours de cet atelier, vous apprendrez à identifier les caractéristiques uniques des vins du Rhône, à travers leurs arômes, leurs saveurs, et leurs textures. Nous débuterons par les bases de la dégustation de vin, vous guidant à travers les étapes essentielles pour éveiller vos sens. Vous découvrirez comment observer la robe du vin, comment en apprécier les arômes et comment déguster pour en saisir toute la complexité.  </p>
        </div>
        <div>
          <h2 class="text-xl font-semibold mb-4">Prochaines sessions</h2>
          <ul class="list-disc pl-5 mb-4">
            <li>22/08/2025 à 18h00 </li>
            <li>Quelque part loin de tout, Lyon, France </li>
          </ul>
          <ul class="list-disc pl-5 mb-4">
            <li>22/08/2025 à 18h00</li>
            <li>Quelque part loin de tout, Lyon, France</li>
          </ul>
        </div>
      </div>
    </div>
</div>
</section>

  </div>
</template>

<script>
export default {
  name: 'WorkshopDetailsPages',
};
</script>

<style scoped>
</style>
