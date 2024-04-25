import apiClient from './apiClient';
import AboutPage from "@/models/AboutPage";

class AboutPageApi {
  getAboutContent() {
    return apiClient.get(`/about/content`);
  }

  editAboutPage(data :AboutPage) {
    return apiClient.put(`/about/edit`, data);
  }
}

export default new AboutPageApi();