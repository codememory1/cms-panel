import { Middleware } from '@nuxt/types';

const auth: Middleware = async function ({ store, $api, redirect }) {
  if (store.getters['modules/global-module/accessToken'].length === 0) {
    try {
      const response = await $api.get('/security/user/info', {
        headers: {
          Authorization: `Bearer ${store.getters['modules/global-module/accessToken']}`
        }
      });

      store.commit('modules/global-module/setUserInfo', response.data.data);
    } catch (e) {
      return redirect('/login');
    }
  }
};

export default auth;
