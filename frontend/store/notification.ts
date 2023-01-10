import { defineStore } from "pinia";

export const useNotificationStore = defineStore({
    id: "notification-store",
    state: () => {
        return {
            status: "",
            message: "",
        };
    },
    actions: {
        addNotification(status: string, message: string) {
            this.status = status;
            this.message = message;
        },
    },
    getters: {
        getStatus: (state) => state.status,
        getMessage: (state) => state.message,
    },
});
