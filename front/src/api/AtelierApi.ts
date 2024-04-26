import apiClient from './apiClient';
import Atelier from "@/models/Atelier";

class AtelierApi {
    atelier() {
        return apiClient.get("/atelier/all");
    }

    getAtelierDetail(idAtelier :bigint, idUser :bigint) {
        return apiClient.get(`/atelier/one/${idAtelier}/${idUser}`);
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

