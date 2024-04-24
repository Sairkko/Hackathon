import User from '@/models/User';
import apiClient from './apiClient';

class UserApi {
  register(user: User) {
    return apiClient.post("/register", user);
  }

  login(user: User) {
    return apiClient.post("/login", user);
  }

  atelier() {
    return apiClient.get("/atelier/all");
  }

  getEvenementByUser(userId :any) {
    return apiClient.get(`/evenement/all/user/${userId}`);
  }
}

export default new UserApi();