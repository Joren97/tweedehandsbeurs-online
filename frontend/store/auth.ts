import { defineStore } from 'pinia'

export const useAuthStore = defineStore({
    id: 'auth-store',
    state: () => {
        return {
            user: null,
        }
    },
    actions: {
        logout() {
            this.user = null;
            const token = useCookie('apiToken');
            token.value = null;
        }
    },
    getters: {
        getUser: state => state.user,
        getRole: state => state.user?.role,
    }
})