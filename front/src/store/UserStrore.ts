import User from '@/models/User';
import { defineStore } from 'pinia';

// Définir une interface pour le state du user.
interface UserState {
  user: User | null; // Remplacez `User` par l'interface ou le type de votre objet utilisateur.
}

export const useUserStore = defineStore('userStore', {
  state: (): UserState => ({
    user: null
  }),
  actions: {
    setUser(user: User | null) { // Remplacez `User` par l'interface ou le type approprié.
      localStorage.setItem('user', JSON.stringify(user))
      this.user = user;
    }
  },
  getters: {
    getUser: (state): User | null => state.user
  }
});