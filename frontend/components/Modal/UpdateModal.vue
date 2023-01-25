<template>
  <v-dialog v-model="isOpen" max-width="500px">
    <v-card>
      <v-card-title>
        <span class="text-h5">{{ modalTitle }}</span>
      </v-card-title>

      <v-card-text>
        <v-container>
          <v-alert v-if="error !== null" outlined type="error">{{ error }}</v-alert>
          <v-col cols="12" sm="6" md="12">
            <slot />
          </v-col>
        </v-container>
      </v-card-text>

      <v-card-actions>
        <v-spacer></v-spacer>
        <v-btn color="blue darken-1" text @click="close">Отмена</v-btn>
        <v-btn color="blue darken-1" text :loading="buttonIsLoading" @click="$emit('update')">
          Изменить
        </v-btn>
      </v-card-actions>
    </v-card>
  </v-dialog>
</template>

<script lang="ts">
import { Component, Vue, Prop, Emit } from 'vue-property-decorator';
import ModalInterface from '~/interfaces/ModalInterface';

@Component
export default class UpdateModal extends Vue implements ModalInterface {
  @Prop({ required: true })
  private readonly modalTitle!: string;

  private error: string | null = null;

  private isOpen: boolean = false;
  private buttonIsLoading: boolean = false;

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
