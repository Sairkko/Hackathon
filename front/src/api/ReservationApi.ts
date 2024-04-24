import User from '@/models/User';
import apiClient from './apiClient';

class UserApi {
  deleteOneReservation(reservationId :bigint, userId :bigint) {
    return apiClient.delete(`/reservation/delete/${reservationId}/${userId}`);
  }
}

export default new UserApi();