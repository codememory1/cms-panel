import { NuxtAxiosInstance } from '@nuxtjs/axios';

declare module '@nuxt/types' {
  // eslint-disable-next-line no-unused-vars
  interface Context {
    $api: NuxtAxiosInstance;
  }

  // eslint-disable-next-line no-unused-vars
  interface NuxtAppOptions {
    $api: NuxtAxiosInstance;
  }
}

declare module 'vue/types/vue' {
  // eslint-disable-next-line no-unused-vars
  interface Vue {
    $api: NuxtAxiosInstance;
  }
}
