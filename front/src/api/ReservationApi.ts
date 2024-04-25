import User from '@/models/User';
import apiClient from './apiClient';

class ReservationApi {
  deleteOneReservation(reservationId :bigint, userId :bigint) {
    return apiClient.delete(`/reservation/delete/${reservationId}/${userId}`);
  }
}

export default new ReservationApi();