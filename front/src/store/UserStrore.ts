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
      this.user = user;
    }
  },
  getters: {
    // Le getter est typé automatiquement, mais vous pouvez spécifier le type de retour si vous le souhaitez.
    getUser: (state): User | null => state.user
  }
});