import { UseFetchOptions } from 'nuxt/dist/app/composables';

export const useCustomFetch = (url: string, options?: UseFetchOptions<unknown>) => {
  const config = useRuntimeConfig();

  return useFetch(config.public.API_BASE_URL + url, {
    ...options,
    async onResponse({ request, response, options }) {
      console.log(response);
    },
    async onResponseError({ request, response, options }) {
      console.log(response);
    },

    async onRequest({ request, options }) {
      const token = useCookie('apiToken');

      if (token.value) {
        options.headers = {
          ...options.headers,
          Authorization: `Bearer ${token.value}`,
        };
      }

      console.log('[fetch request]');
    },
    async onRequestError({ request, options, error }) {
      console.log('[fetch request error]');
    },
  });
};
