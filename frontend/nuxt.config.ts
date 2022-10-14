// https://v3.nuxtjs.org/api/configuration/nuxt.config
export default defineNuxtConfig({
  app: {
    head: {
      script: [{ src: 'assets/js/main.js', body: true }],
    },
  },
  css: ['~/assets/scss/main.scss'],
});
