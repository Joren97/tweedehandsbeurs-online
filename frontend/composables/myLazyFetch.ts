import { AsyncDataOptions, UseFetchOptions } from "nuxt/dist/app/composables";

export const myLazyFetch = (url: Function, fetchOptions?: UseFetchOptions<unknown>) => {
    const config = useRuntimeConfig();

    return useLazyFetch(config.public.API_BASE_URL + url(), {
        ...fetchOptions,
        async onRequest({ request, options }) {
            const token = useCookie("apiToken");

            if (token.value) {
                options.headers = {
                    ...options.headers,
                    Authorization: `Bearer ${token.value}`,
                };
            }

            options.headers = {
                ...options.headers,
                "Accept": "application/json",
            }
        },
        async onRequestError({ request, options, error }) {
        },
        async onResponse({ request, response, options }) {
            if (response.status === 401) {
                const token = useCookie('apiToken');
                token.value = null;
                navigateTo('/login')
            }
        }
    })
};