import apiClient from './apiClient';

class ProductApi {
    removeProduct(id: string){
        return apiClient.delete(`/product/delete/${id}`);
    }

    addProduct(data: object) {
        return apiClient.post('/product/new', data);
    }
}
export default new ProductApi();