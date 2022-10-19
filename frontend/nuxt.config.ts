// https://v3.nuxtjs.org/api/configuration/nuxt.config
export default defineNuxtConfig({
  head: {
    link: [
      {
        rel: 'stylesheet',
        href: '<link href="https://fonts.googleapis.com/css2?family=Nunito:wght@300;400;500;600;700&display=swap" rel="stylesheet">',
      },
    ],
  },
  app: {
    head: {
      script: [{ src: 'assets/js/main.js', body: true }],
    },
  },
  css: ['~/assets/scss/main.scss'],
});
