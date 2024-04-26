import User from '@/models/User';
import apiClient from './apiClient';

class UserApi {
  register(user: User) {
    return apiClient.post("/register", user);
  }

  login(user: User) {
    return apiClient.post("/login", user);
  }

  getEvenementByUser(userId :any) {
    return apiClient.get(`/evenement/all/user/${userId}`);
  }

  resetPassword(email :any) {
    return apiClient.post('/forgot-password', {'email': email});
  }

  changePassword(el: any) {
    return apiClient.post('/reset-password', el);
  }
}

export default new UserApi();
