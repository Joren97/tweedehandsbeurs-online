import { defineStore } from 'pinia'

export const useNotificationStore = defineStore({
    id: 'notification-store',
    state: () => {
        return {
            status: "",
            message: "",
        }
    },
    actions: {},
    getters: {
        getStatus: state => state.status,
        getMessage: state => state.message,
    }
})