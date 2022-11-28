import { UseFetchOptions } from '#app'
import { NitroFetchRequest } from 'nitropack'
import { KeyOfRes } from 'nuxt/dist/app/composables/asyncData'

export function myFetch<T>(
  request: NitroFetchRequest,
  opts?:
    | UseFetchOptions<
      T extends void ? unknown : T,
      (res: T extends void ? unknown : T) => T extends void ? unknown : T,
      KeyOfRes<
        (res: T extends void ? unknown : T) => T extends void ? unknown : T
      >
    >
    | undefined
) {
  const config = useRuntimeConfig();
  const token = useCookie("apiToken");
  const headers = token.value ? { 'Authorization': `Bearer ${token.value}` } : {};

  return useFetch<T>(request, {
    baseURL: config.public.API_BASE_URL,
    headers,
    initialCache: false,
    ...opts,
  })
}