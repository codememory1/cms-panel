<template>
  <v-dialog v-model="isOpen" max-width="500px">
    <v-card>
      <v-card-title class="text-h5">{{ modalTitle }}</v-card-title>
      <v-alert v-if="error !== null" outlined type="error">{{ error }}</v-alert>
      <v-card-text>Подтверждаете ли вы удаление сущности ?</v-card-text>
      <v-card-actions>
        <v-spacer></v-spacer>
        <v-btn color="blue darken-1" text @click="close">Отмена</v-btn>
        <v-btn color="blue darken-1" text :loading="buttonIsLoading" @click="$emit('delete')">
          Подтвердить
        </v-btn>
        <v-spacer></v-spacer>
      </v-card-actions>
    </v-card>
  </v-dialog>
</template>

<script lang="ts">
import { Component, Vue, Prop, Emit } from 'vue-property-decorator';

@Component
export default class DeleteModal extends Vue {
  @Prop({ required: true })
  private readonly modalTitle!: string;

  private isOpen: boolean = false;
  private buttonIsLoading: boolean = false;
  private error: string | null = null;

  public open(): void {
    this.isOpen = true;
  }

  @Emit('close')
  public close(): void {
    this.isOpen = false;
  }

  public setButtonIsLoading(is: boolean): void {
    this.buttonIsLoading = is;
  }

  public setError(error: string): void {
    this.error = error;
  }
}
</script>
