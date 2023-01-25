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
          <v-list-item v-for="item in items" :key="item.title" @click="toRoute(item)">
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
      <v-main class="overflow-auto">
        <Nuxt />
      </v-main>
    </v-layout>
  </v-app>
</template>

<script lang="ts">
import { Component, Vue } from 'vue-property-decorator';
import UserInfoResponseType from '~/types/user-info-response-type';

type NavigationItem = {
  title: string;
  link: string;
};

@Component
export default class AdminLayout extends Vue {
  private readonly items: Array<NavigationItem> = [
    { title: 'Пользователи', link: '/users' },
    { title: 'Номера телефонов', link: '/phones' },
    { title: 'Роли пользователей', link: '/roles' },
    { title: 'Разрешения ролей', link: '/permissions' },
    { title: 'Логирование действий', link: '/action-logs' }
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

  private toRoute(item: NavigationItem): void {
    this.$router.push({ path: item.link });
  }
}
</script>
