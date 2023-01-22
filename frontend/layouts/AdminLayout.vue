<template>
  <v-app>
    <v-layout>
      <v-navigation-drawer :mini-variant="false" permanent>
        <v-list-item class="px-3">
          <v-list-item-avatar class="navigation-avatar-wrapper">
            <v-img src="/images/admin-logo.png" />
          </v-list-item-avatar>

          <v-list-item-title class="navigation__admin-title">
            {{ authorizedUserInfo.name }}
          </v-list-item-title>
        </v-list-item>

        <v-divider />

        <v-list dense>
          <v-list-item v-for="item in items" :key="item.title" link>
            <v-list-item-content>
              <v-list-item-title>{{ item.title }}</v-list-item-title>
            </v-list-item-content>
          </v-list-item>
          <v-list-item link @click="logout">
            <v-list-item-content>
              <v-list-item-title>Выйти из аккаунта</v-list-item-title>
            </v-list-item-content>
          </v-list-item>
        </v-list>
      </v-navigation-drawer>
      <v-main>
        <Nuxt />
      </v-main>
    </v-layout>
  </v-app>
</template>

<script lang="ts">
import { Component, Vue } from 'vue-property-decorator';
import UserInfoResponseType from '~/types/user-info-response-type';

@Component
export default class AdminLayout extends Vue {
  private readonly items = [
    { title: 'Главная' },
    { title: 'Пользователи' },
    { title: 'Номера телефонов' },
    { title: 'Роли пользователей' },
    { title: 'Разрешения ролей' },
    { title: 'Логирование действий' }
  ];

  private get authorizedUserInfo(): UserInfoResponseType {
    return this.$store.getters['modules/global-module/userInfo'];
  }

  private logout(): void {
    this.$router.push({ name: 'login' });

    setTimeout(() => {
      this.$store.commit('modules/global-module/setAccessToken', '');
      this.$store.commit('modules/global-module/setUserInfo', null);
    }, 1000);
  }
}
</script>
