import { ohMyFetchResponse } from '~~/types/ohMyFetchResponse';
import { useAuthStore } from './../store/auth';
export default defineNuxtRouteMiddleware(async (to, from) => {
    const authStore = useAuthStore();
    const cookie = useCookie('apiToken');
    const authLevel = to.meta.authLevel;


    // Check if user is logged in, else redirect to login page
    if (!cookie.value) {
        return navigateTo('/login');
    }

    // Check if user object is set, else fetch it
    if (!authStore.user || authStore.user == null) {
        try {
            const { data: { value } } = await myFetch("/api/auth/userinfo", {
                initialCache: false,
            }) as ohMyFetchResponse;
            if (value && value.data) {
                authStore.user = value.data;
            }
        } catch (error) {
            return navigateTo('/login');
        }
    }

    // Check the auth level of the route
    if (authLevel) {
        // If authlevel is 'employee' only admin and employee can enter
        if (authLevel === 'employee' && authStore.user.role !== 'admin' && authStore.user.role !== 'employee') {
            return abortNavigation("You don't have permission to access this page");
        }
        // If authlevel is 'admin' only admin can enter
        else if (authLevel === 'admin' && authStore.user.role !== 'admin') {
            return abortNavigation("You don't have permission to access this page");
        }
    }

    return true;
})