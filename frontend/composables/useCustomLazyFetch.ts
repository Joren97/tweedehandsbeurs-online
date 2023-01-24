import { UseFetchOptions } from "nuxt/dist/app/composables";
import { FetchOptions } from "ohmyfetch";

export const useCustomLazyFetch = (url: string, options?: UseFetchOptions<unknown>) => {
  const config = useRuntimeConfig();

  return useLazyFetch(config.public.API_BASE_URL + url, {
    ...options,
    async onResponse({ request, response, options }) {
    },
    async onResponseError({ request, response, options }) {
    },

    async onRequest({ request, options }) {
      const token = useCookie("apiToken");

      if (token.value) {
        options.headers = {
          ...options.headers,
          Authorization: `Bearer ${token.value}`,
        };
      }
    },
    async onRequestError({ request, options, error }) {
    },
  });
};
