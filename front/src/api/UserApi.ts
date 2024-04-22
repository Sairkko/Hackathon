import apiClient from './apiClient';

class UserApi {
  register() {
    return apiClient.get("/users");
  }
}

export default new UserApi();