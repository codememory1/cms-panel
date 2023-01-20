import colors from 'vuetify/es5/util/colors';
import { NuxtConfig } from '@nuxt/types';

const config: NuxtConfig = {
  server: {
    host: '0.0.0.0',
    port: 3000
  },

  head: {
    title: 'CMS Admin',

    htmlAttrs: {
      lang: 'ru'
    },

    meta: [
      { charset: 'utf-8' },
      { name: 'viewport', content: 'width=device-width, initial-scale=1' },
      { hid: 'description', name: 'description', content: '' },
      { name: 'format-detection', content: 'telephone=no' }
    ],
    link: [{ rel: 'icon', type: 'image/x-icon', href: '/favicon.ico' }]
  },

  privateRuntimeConfig: {
    apiServerHost: 'http://nginx/api/v1/'
  },

  publicRuntimeConfig: {
    apiClientHost: '/api/v1/'
  },

  css: ['~/assets/scss/main.scss'],

  plugins: ['~/plugins/api.ts', '~/store/index.ts'],

  components: true,

  buildModules: ['@nuxt/typescript-build', '@nuxtjs/vuetify'],

  modules: ['@nuxtjs/axios'],

  vuetify: {
    theme: {
      dark: true,
      themes: {
        dark: {
          primary: colors.blue.darken2,
          accent: colors.grey.darken3,
          secondary: colors.amber.darken3,
          info: colors.teal.lighten1,
          warning: colors.amber.base,
          error: colors.deepOrange.accent4,
          success: colors.green.accent3
        }
      }
    }
  },

  build: {}
};

export default config;
