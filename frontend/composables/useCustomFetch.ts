import { UseFetchOptions } from 'nuxt/dist/app/composables';

export const useCustomFetch = (url: string, options?: UseFetchOptions<unknown>) => {
  const config = useRuntimeConfig();

  return useFetch(config.public.API_BASE_URL + url, {
    ...options,
    async onResponse({ request, response, options }) {
      if (response.status === 401) {
        const token = useCookie('apiToken');
        token.value = null;
        navigateTo('/login')
      }
    },
    async onResponseError({ request, response, options }) {
    },

    async onRequest({ request, options }) {
      const token = useCookie('apiToken');

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
  });
};
