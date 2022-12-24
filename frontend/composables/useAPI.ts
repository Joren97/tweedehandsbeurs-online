import { FetchOptions } from "ohmyfetch";

const cookie: string = "apiToken";

/**
 * Api call using nuxt `useFetch`
 *
 * @see {@link https://github.com/unjs/ohmyfetch#readme} ~ ohmyfetch Docs
 * @param url
 * @param options
 */
export const useApi = async (url: string, options?: FetchOptions) => {
    const config = useRuntimeConfig();
    const token = useCookie(cookie)?.value;

    // Here we will create a default set of headers for every request
    // if present we will also spread the `headers` set by the user
    // then we will delete them to avoid collision in next spread
    const headers: HeadersInit = {
        Accept: "application/json",
        "Cache-Control": "no-cache",
        Authorization: `Bearer ${token}`,
        ...options?.headers,
    };

    // At this point all the `headers` passed by the user where correctly
    // set in the defaults, now we will spread `options` to remove the
    // `headers` attribute so we don't spread it again in `useFetch`
    const opts: FetchOptions = options ? (({ headers, ...opts }) => opts)(options) : null;

    return useFetch(url, {
        baseURL: config.public.API_BASE_URL,
        credentials: "include", // Allow browser to handle cookies
        headers,
        ...opts,
    });
};
