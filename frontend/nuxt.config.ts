// https://v3.nuxtjs.org/api/configuration/nuxt.config
export default defineNuxtConfig({
    ssr: false,
    target: 'static',
    app: {
        head: {
            link: [{
                rel: 'stylesheet', href: 'https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css'
            }],
            script: [
                { src: "https://kit.fontawesome.com/c0db9975db.js", crossorigin: "anonymous" },
                { src: "https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js" },
                { src: "./assets/vendor/bootstrap/js/bootstrap.bundle.min.js", body: true },
                { src: "./assets/vendor/jquery-easing/jquery.easing.min.js", body: true },
                { src: "./assets/js/sb-admin-2.js", body: true },
            ]
        }
    },
    css: [
        '~/assets/scss/sb-admin-2.scss'
    ],
    modules: [
        [
            '@pinia/nuxt',
            {
                autoImports: [
                    // automatically imports `defineStore`
                    'defineStore', // import { defineStore } from 'pinia'
                    // automatically imports `defineStore` as `definePiniaStore`
                    ['defineStore', 'definePiniaStore'], // import { defineStore as definePiniaStore } from 'pinia'
                ],
            },
        ],
    ],
    typescript: {
        typeCheck: true
    }
})
