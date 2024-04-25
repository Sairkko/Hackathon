import Event from '@/models/Event';
import apiClient from './apiClient';

class EventApi {
    getEvents() {
        return apiClient.get("/evenement/all");
    }

    editEvent(evenement: Event) {
        return apiClient.put(`/evenement/edit/${evenement.id}`, evenement)
    }

    getEvent(evenement: Event) {
        return apiClient.put(`/evenement/one/${evenement.id}`)
    }

    createEvent(evenement: Event) {
        return apiClient.post("/evenement/new", evenement);
    }

}
export default new EventApi();

