<template>
  <v-dialog v-model="isOpen" max-width="500px">
    <v-card>
      <v-card-title>
        <span class="text-h5">Список транзакций</span>
      </v-card-title>

      <v-card-text>
        <v-container>
          <v-alert v-if="error !== null" outlined type="error">{{ error }}</v-alert>
        </v-container>
        <v-col cols="12" sm="6" md="12">
          <v-row v-if="isLoading" justify="center">
            <v-progress-circular indeterminate color="primary" />
          </v-row>
          <template v-else>
            <p v-if="data.length === 0" class="text-center">Пустой</p>
            <v-list v-for="item in data" :key="item.id" subheader two-line>
              <v-list-item>
                <v-list-item-content>
                  <v-list-item-title>Тип:</v-list-item-title>
                  <v-list-item-subtitle>{{ item.type }}</v-list-item-subtitle>
                </v-list-item-content>
              </v-list-item>
              <v-list-item>
                <v-list-item-content>
                  <v-list-item-title>Имя карточки:</v-list-item-title>
                  <v-list-item-subtitle>{{ item.card.type }}</v-list-item-subtitle>
                </v-list-item-content>
              </v-list-item>
              <v-list-item>
                <v-list-item-content>
                  <v-list-item-title>Последние цифры карты:</v-list-item-title>
                  <v-list-item-subtitle>{{ item.card.last_number }}</v-list-item-subtitle>
                </v-list-item-content>
              </v-list-item>
              <v-list-item>
                <v-list-item-content>
                  <v-list-item-title>Сумма:</v-list-item-title>
                  <v-list-item-subtitle>{{ item.sum }}</v-list-item-subtitle>
                </v-list-item-content>
              </v-list-item>
              <v-list-item>
                <v-list-item-content>
                  <v-list-item-title>Время завершения:</v-list-item-title>
                  <v-list-item-subtitle>{{ item.completed_on_time }}</v-list-item-subtitle>
                </v-list-item-content>
              </v-list-item>

              <v-divider />
            </v-list>
            <v-pagination
              v-model="page"
              :length="numberPages"
              :total-visible="7"
              @input="$emit('changePage', $event)"
            />
          </template>
        </v-col>
      </v-card-text>
    </v-card>
  </v-dialog>
</template>

<script lang="ts">
import { Component, Prop, Vue } from 'vue-property-decorator';

@Component
export default class PhoneTransactionModal extends Vue {
  @Prop({ required: false, default: 1 })
  private readonly numberPages!: number;

  private page: number = 1;
  private isOpen: boolean = false;

  @Prop({ required: false, default: null })
  private error!: string | null;

  @Prop({ required: false, default: true })
  private isLoading!: boolean;

  @Prop({ required: false, default: null })
  private data!: any;

  public open(): void {
    this.isOpen = true;
  }
}
</script>
