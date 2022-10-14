import { useAuthStore } from './../store/auth';
export default defineNuxtRouteMiddleware(async (to, from) => {
    const authStore = useAuthStore();
    const cookie = useCookie('apiToken');
    const authLevel = to.meta.authLevel;

    console.log("meta", to.meta);

    console.log("authLevel: " + authLevel);


    // Check if user is logged in, else redirect to login page
    if (!cookie.value) {
        return navigateTo('/login');
    }

    // Check if user object is set, else fetch it
    if (!authStore.user) {
        try {
            const { data } = await useCustomFetch("http://localhost:8000/api/auth/userinfo");
            authStore.user = data;
        } catch (error) {
            return navigateTo('/login');
        }
    }

    // Check the auth level of the route
    if (authLevel) {
        // If authlevel is 'employee' only admin and employee can enter
        if (authLevel === 'employee' && authStore.user.role !== 'admin' && authStore.user.role !== 'employee') {
            abortNavigation();
        }
        // If authlevel is 'admin' only admin can enter
        else if (authLevel === 'admin' && authStore.user.role !== 'admin') {
            abortNavigation();
        }
    }

    return true;
})