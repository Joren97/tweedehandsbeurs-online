import { defineStore } from 'pinia'

export const useAuthStore = defineStore({
    id: 'auth-store',
    state: () => {
        return {
            user: null,
        }
    },
    actions: {},
    getters: {
        getUser: state => state.user,
        getRole: state => state.user?.role,
    }
})