import User from '@/models/User';
import apiClient from './apiClient';
import Reservation from '@/models/Reservation';

class UserApi {
  deleteOneReservation(reservationId :bigint, userId :bigint) {
    return apiClient.delete(`/reservation/delete/${reservationId}/${userId}`);
  }

  reservationByEvenement(evenementId :bigint, ) {
    return apiClient.delete(`/reservation/show/${evenementId}`);
  }

  getReservations() {
    return apiClient.get('/reservation/all');
  }

  getReservation(reservation: Reservation) {
    return apiClient.get(`/reservation/one/${reservation.id}`);
  }

  createReservation(reservation: Reservation) {
    return apiClient.post('/reservation/new');
  }

  validPaiement(reservation: Reservation) {
    return apiClient.put(`/reservation/paid/${reservation.id}`);
  }
}

export default new UserApi();
