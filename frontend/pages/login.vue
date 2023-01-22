<template>
  <v-app>
    <v-layout>
      <v-flex xs12 sm6 offset-sm3 align-center justify-center class="auth-wrapper">
        <v-card class="d-flex align-center">
          <v-col class="pa-10">
            <h3 class="text-center mb-5">Вход в административную панель</h3>
            <v-alert v-if="error.length !== 0" outlined type="error">{{ error }}</v-alert>
            <v-form class="sm6 justify-center">
              <v-text-field v-model="email" :error="emailIsError" label="Почта" required />
              <v-text-field
                v-model="password"
                :error="passwordIsError"
                label="Пароль"
                type="password"
                required
              />
              <v-btn :loading="buttonsIsLoading" color="primary" class="justify-end" @click="auth">
                Войти
              </v-btn>
            </v-form>
          </v-col>
        </v-card>
      </v-flex>
    </v-layout>
  </v-app>
</template>

<script lang="ts">
import { Component, Vue } from 'vue-property-decorator';

@Component
export default class Login extends Vue {
  private emailIsError: boolean = false;
  private passwordIsError: boolean = false;
  private email: string = '';
  private password: string = '';
  private buttonsIsLoading: boolean = false;
  private error: string = '';

  private auth(): void {
    this.emailIsError = this.email.length === 0;
    this.passwordIsError = this.password.length === 0;

    if (!this.emailIsError && !this.passwordIsError) {
      this.buttonsIsLoading = true;

      this.$api
        .post('security/auth', {
          email: this.email,
          password: this.password
        })
        .then((response) => {
          this.buttonsIsLoading = false;

          this.successAuth(response);
        })
        .catch((error) => {
          this.buttonsIsLoading = false;

          this.error = error.response.data.error;
        });
    }
  }

  private successAuth(response: any): void {
    this.$store.commit('modules/global-module/setAccessToken', response.data.data.access_token);

    this.$api
      .get('/security/user/info', {
        headers: {
          Authorization: `Bearer ${response.data.data.access_token}`
        }
      })
      .then((response) => {
        this.$store.commit('modules/global-module/setUserInfo', response.data.data);

        this.$router.push({ name: 'index' });
      });
  }
}
</script>

<style>
.auth-wrapper {
  margin-top: auto;
  margin-bottom: auto;
}
</style>
