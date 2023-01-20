export default {
  namespaced: true,

  state: {
    access_token: ''
  },

  getters: {
    accessToken(state: any) {
      return state.access_token;
    }
  },

  mutations: {
    setAccessToken(state: any, token: string): void {
      state.access_token = token;
    }
  }
};
