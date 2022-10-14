export default defineNuxtRouteMiddleware((to, from) => {
    const cookie = useCookie('apiToken');

    if (!cookie.value) {
        return navigateTo('/login');
    }

    return true;
})