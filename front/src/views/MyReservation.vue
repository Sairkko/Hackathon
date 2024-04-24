<template>
    <div id="my-reservation">
        <div class="header">
            <h1>Mes réservations en attentes de paiement</h1>
            <p>Pour vendre un demi-rein et ainsi, pouvoir picoler, donne ta moula à ce paypal: test@test.fr</p>
        </div>

        <h2 @click="onClick">Cliquer pour charger les données</h2>

        <!-- Réservations validées -->
        <section>
            <h2>Réservations Validées</h2>
            <div v-if="paidReservations.length">
                <div v-for="reservation in paidReservations" :key="reservation.id">
                    <p>{{ reservation.description }} - {{ reservation.date }}</p>
                </div>
            </div>
            <p v-else>Aucune réservation validée.</p>
        </section>

        <!-- Réservations en attente de paiement -->
        <section>
            <h2>Réservations en Attente de Paiement</h2>
            <div v-if="unpaidReservations.length">
                <div v-for="reservation in unpaidReservations" :key="reservation.id">
                    <p>{{ reservation.description }} - {{ reservation.date }}</p>
                </div>
            </div>
            <p v-else>Aucune réservation en attente de paiement.</p>
        </section>

    </div>
</template>

<script>
import { ref, computed } from 'vue';
import userApi from "@/api/UserApi";

export default {
    setup() {
        const events = ref({});

        const onClick = () => {
            const userStr = localStorage.getItem('user');
            if (userStr) {
                const user = JSON.parse(userStr);
                const userId = user.id;

                userApi.getEvenementByUser(userId).then(response => {
                    events.value = response.data.data;
                });
            } else {
                console.error("No user found in localStorage");
            }
        };

        const paidReservations = computed(() =>
            events.value.reservations ? events.value.reservations.filter(res => res.isPaid) : []
        );
        const unpaidReservations = computed(() =>
            events.value.reservations ? events.value.reservations.filter(res => !res.isPaid) : []
        );

        return {
            onClick,
            paidReservations,
            unpaidReservations,
            events
        };
    }
}
</script>

<style>
</style>
