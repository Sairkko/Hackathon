import User from '@/models/User';
import apiClient from './apiClient';

class UserApi {
  deleteOneReservation(reservationId :bigint, userId :bigint) {
    return apiClient.delete(`/reservation/delete/${reservationId}/${userId}`);
  }

  reservationByEvenement(evenementId :bigint, ) {
    return apiClient.delete(`/reservation/show/${evenementId}`);
  }
}

export default new UserApi();