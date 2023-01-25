<template>
  <v-dialog v-model="isOpen" max-width="500px">
    <template #activator="{ on, attrs }">
      <v-btn color="primary" class="ml-3 mr-3" dark v-bind="attrs" v-on="on">
        {{ buttonTitle }}
      </v-btn>
    </template>
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
        <v-btn color="blue darken-1" text @click="isOpen = false">Отмена</v-btn>
        <v-btn color="blue darken-1" text :loading="buttonIsLoading" @click="$emit('create')">
          Создать
        </v-btn>
      </v-card-actions>
    </v-card>
  </v-dialog>
</template>

<script lang="ts">
import { Component, Vue, Prop } from 'vue-property-decorator';

@Component
export default class CreateModal extends Vue {
  @Prop({ required: true })
  private readonly modalTitle!: string;

  @Prop({ required: true })
  private readonly buttonTitle!: string;

  private error: string | null = null;

  private isOpen: boolean = false;
  private buttonIsLoading: boolean = false;

  public open(): void {
    this.isOpen = true;
  }

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
