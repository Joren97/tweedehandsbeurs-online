// https://v3.nuxtjs.org/api/configuration/nuxt.config
export default defineNuxtConfig({
    app: {
        head: {
            link: [{
                rel: 'stylesheet', href: 'https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css'
            }],
            script: [
                { src: "https://kit.fontawesome.com/c0db9975db.js", crossorigin: "anonymous" },
                { src: "assets/vendor/jquery/jquery.min.js", body: true },
                { src: "assets/vendor/bootstrap/js/bootstrap.bundle.min.js", body: true },
                { src: "assets/vendor/jquery-easing/jquery.easing.min.js", body: true },
                { src: "assets/vendor/chart.js/Chart.min.js" },
                { src: "assets/js/sb-admin-2.js", body: true },
                { src: "assets/js/demo/chart-bar-demo.js", body: true },
                { src: "assets/js/demo/chart-pie-demo.js", body: true },
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
    ]
})
