import apiClient from './apiClient';
import Atelier from "@/models/Atelier";

class AtelierApi {
    atelier() {
        return apiClient.get("/atelier/all");
    }

    removeAtelier(id: string){
        return apiClient.delete(`/atelier/delete/${id}`);
    }

    product() {
        return apiClient.get("/product/all");
    }

    atelierById(id: string, data: Atelier) {
        return apiClient.put(`/atelier/edit/${id}`, data);
    }

    postAtelier(data: Atelier) {
        return apiClient.post('/atelier/new', data);
    }
}
export default new AtelierApi();

