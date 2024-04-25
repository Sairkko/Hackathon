import apiClient from './apiClient';

class ProductApi {
    removeProduct(id: string){
        return apiClient.delete(`/product/delete/${id}`);
    }

    addProduct(data: object) {
        return apiClient.post('/product/new', data);
    }
    editProduct(id: string, data: object) {
        console.log(data)
        return apiClient.put(`/product/edit/${id}`, data);
    }
}
export default new ProductApi();