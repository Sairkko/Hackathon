<template>
    <div id="my-reservation">
        <section class="px-4 py-16">
            <h1 class="text-3xl font-bold text-center text-dark-red mb-10">Mes réservations en attentes de paiement</h1>
            <p class="mt-4 mx-16 mb-10 text-center font-semibold">
                Pour pouvoir reserver sa place envoie le prix à ce paypal: atelier-vins@olivier-bonneton.com
            </p>
            <template v-if="unpaidReservations.length">
                <div v-for="event in unpaidReservations" :key="event.id" class="flex flex-row overflow-hidden rounded-lg shadow-md mb-8 mx-4 md:mx-auto max-w-4xl">
                    <img class="w-full md:w-2/5 lg:w-1/5 object-cover" src="../assets/atelier.png" alt="Wine tasting experience">
                    <div class="w-full md:w-3/5 lg:w-4/5 p-4 bg-white flex flex-row justify-between gap-4">
                        <div class="flex flex-col">
                            <h3 class="text-xl font-bold text-start text-red">{{ event.atelier_content.nom }}</h3>
                            <p class="text-dark-red text-start mb-4">
                                {{ formatDate(event.date_debut) }} <br> Durée: {{ calculateDuration(event.date_debut, event.date_fin) }}
                            </p>
                            <p class="text-red text-start mb-4">
                                <font-awesome-icon :icon="'hourglass-start'" />
                                En attente de paiement
                            </p>

                        </div>
                        <div class="flex flex-col justify-center gap-4">
                            <button @click="deleteOneReservation(event.reservation.id)" style="width: 160px" class="border text-white border-grey bg-grey py-2 px-4 rounded hover:bg-red-700 transition duration-300">Annuler</button>
                            <a :href="`/atelier/${event.atelier_content.id}`" style="width: 160px" class="border text-white border-red bg-red py-2 px-4 rounded hover:bg-red-700 transition duration-300">Détails</a>
                        </div>
                    </div>
                </div>
            </template>
            <p v-else class="text-center">Aucune réservation en attente de paiement.</p>
        </section>

        <section class="px-4 mb-10">
            <h2 class="text-3xl font-bold text-center text-dark-red mb-10">Mes réservations validées</h2>
            <template v-if="paidReservations.length">
                <div v-for="event in paidReservations" :key="event.id" class="flex flex-row overflow-hidden rounded-lg shadow-md mb-8 mx-4 md:mx-auto max-w-4xl">
                    <img class="w-full md:w-2/5 lg:w-1/5 object-cover" src="../assets/atelier.png" alt="Wine tasting experience">
                    <div class="w-full md:w-3/5 lg:w-4/5 p-4 bg-white flex flex-row justify-between gap-4">
                        <div class="flex flex-col">
                            <h3 class="text-xl font-bold text-start text-red">{{ event.atelier_content.nom }}</h3>
                            <p class="text-dark-red text-start mb-4">
                                {{ formatDate(event.date_debut) }} <br><x></x> Durée: {{ calculateDuration(event.date_debut, event.date_fin) }}
                            </p>
                            <p class="text-green text-start mb-4">
                                <font-awesome-icon :icon="'check'" />
                                Réservation effectuée
                            </p>
                        </div>
                        <div class="flex flex-col justify-center gap-4">
                            <a :href="`/atelier/${event.atelier_content.id}`" style="width: 160px" class="border text-white border-red bg-red py-2 px-4 rounded hover:bg-red-700 transition duration-300">Détails</a>
                        </div>
                    </div>
                </div>
            </template>
            <p v-else class="text-center">Aucune réservation validée.</p>
        </section>
    </div>
</template>



<script>
import {ref, computed, onMounted} from 'vue';
import userApi from "@/api/UserApi";
import reservationApi from "@/api/ReservationApi";
import {differenceInMinutes, format} from 'date-fns';
export default {
    setup() {
        const events = ref([]);

        const paidReservations = computed(() =>
            events.value.filter(event => event.reservation && event.reservation.is_paid)
        );
        const unpaidReservations = computed(() =>
            events.value.filter(event => event.reservation && !event.reservation.is_paid)
        );

        const formatDate = (date) => {
            return format(new Date(date), 'dd-MM-yyyy à HH:mm');
        }

        const calculateDuration = (startDate, endDate) => {
            const start = new Date(startDate);
            const end = new Date(endDate);
            const diffMinutes = differenceInMinutes(end, start);

            const hours = Math.floor(diffMinutes / 60);
            const minutes = diffMinutes % 60;

            return `${hours}h${minutes < 10 ? '0' + minutes : minutes}`;
        }

        const userStr = localStorage.getItem('user');
        const user = JSON.parse(userStr);
        const userId = user.id;

        onMounted(async () => {
            fetchReservation()
        })

        const fetchReservation = () => {
            if (userId) {
                userApi.getEvenementByUser(userId).then(response => {
                    events.value = response.data.data;
                    console.log(events.value);
                }).catch(error => {
                    console.error('Error fetching events:', error);
                });
            } else {
                console.error("No user found in localStorage");
            }
        }

        const deleteOneReservation = (reservationId) => {
            const user = JSON.parse(userStr);
            const userId = user.id;
            reservationApi.deleteOneReservation(reservationId, userId)
                .then(() => {
                    fetchReservation()
                })
                .catch(error => {
                    console.error('Error fetching events:', error);
                });
        }

        return {
            paidReservations,
            unpaidReservations,
            events,
            deleteOneReservation,
            formatDate,
            calculateDuration
        };
    }
}
</script>


<style>
</style>
