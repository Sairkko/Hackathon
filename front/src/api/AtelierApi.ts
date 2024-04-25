import apiClient from './apiClient';

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

    atelierById(id: string, thematique?: string, products?: string[], nom?: string, description?: string, prix?: number) {
        return apiClient.put(`/atelier/edit/${id}`, {
            thematique: thematique,
            products: products,
            nom: nom,
            description: description,
            prix: prix
        })
    }
}
export default new AtelierApi();

