import UserInfoResponseType from '~/types/user-info-response-type';

export default {
  namespaced: true,

  state: {
    access_token: '',
    user_info: null
  },

  getters: {
    accessToken(state: any): string {
      return state.access_token;
    },

    userInfo(state: any): UserInfoResponseType | null {
      return state.user_info;
    }
  },

  mutations: {
    setAccessToken(state: any, token: string): void {
      state.access_token = token;
    },

    setUserInfo(state: any, info: UserInfoResponseType | null): void {
      state.user_info = info;
    }
  }
};
