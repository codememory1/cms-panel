import { NuxtApp } from '@nuxt/types/app';

export default function (
  { $axios, $config, $isServer }: NuxtApp,
  inject: (key: string, value: any) => void
) {
  const axios = $axios.create({
    baseURL: $isServer ? $config.apiServerHost : $config.apiClientHost,
    headers: {
      'X-Requested-With': 'XMLHttpRequest'
    }
  });

  axios.defaults.withCredentials = false;

  inject('api', axios);
}
