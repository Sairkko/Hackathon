import User from '@/models/User';
import apiClient from './apiClient';

class UserApi {
  register(user: User) {
    return apiClient.post("/register", user);
  }

  login(user: User) {
    return apiClient.post("/login", user);
  }
}

export default new UserApi();