import Vuex from 'vuex';
import Vue from 'vue';
import GlobalModule from '~/store/modules/global-module';

Vue.use(Vuex);

export default () => {
  return new Vuex.Store({
    modules: {
      'modules/global-module': GlobalModule
    }
  });
};
