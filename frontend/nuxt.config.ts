// https://v3.nuxtjs.org/api/configuration/nuxt.config
export default defineNuxtConfig({
  ssr: false,
  target: 'static',
  app: {
    head: {
      link: [
        {
          rel: 'stylesheet',
          href: 'https://fonts.googleapis.com/css2?family=Nunito:wght@300;400;500;600;700&display=swap',
        },
      ],
      script: [
        { src: 'https://kit.fontawesome.com/c0db9975db.js', crossorigin: 'anonymous' },
        { src: 'https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js' },
        {
          src: 'https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js',
          crossorigin: 'anonymous',
          integrity: 'sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM',
        },
        { src: 'assets/js/sb-admin-2.js', body: true },
        { src: 'assets/js/main.js', body: true },
      ],
    },
  },
  css: ['~/assets/scss/main.scss'],
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
    typeCheck: true,
  },
  runtimeConfig: {
    public: {
      API_BASE_URL: process.env.API_BASE_URL || 'http://localhost:8000',
    },
  },
});
