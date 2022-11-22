import { NitroFetchRequest, TypedInternalResponse } from "nitropack";
import { FetchOptions } from "ohmyfetch";

export const useAPI = <T = unknown, R extends NitroFetchRequest = NitroFetchRequest>(request: R, opts?: FetchOptions | undefined): Promise<TypedInternalResponse<R, T>> => {
    const config = useRuntimeConfig()

    const token = useCookie("apiToken");
    let headers = {};
    if (token.value) headers = { 'Authorization': `Bearer ${token.value}` }

    const customFetch = $fetch.create({
        baseURL: config.public.API_BASE_URL,
        headers,
        onRequest({ request, options }) {
            console.log("Intercepted API", request);
        }
    })

    return customFetch(request, opts)
}