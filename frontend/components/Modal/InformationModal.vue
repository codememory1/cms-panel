<template>
  <v-dialog v-model="isOpen" max-width="500px">
    <v-card>
      <v-card-title>
        <span class="text-h5">{{ modalTitle }}</span>
      </v-card-title>

      <v-card-text>
        <v-container>
          <v-col cols="12" sm="6" md="12">
            <slot />
          </v-col>
        </v-container>
      </v-card-text>

      <v-card-actions>
        <v-spacer></v-spacer>
        <v-btn color="blue darken-1" text @click="isOpen = false">Закрыть</v-btn>
      </v-card-actions>
    </v-card>
  </v-dialog>
</template>

<script lang="ts">
import { Component, Vue, Prop } from 'vue-property-decorator';
import ModalInterface from '~/interfaces/ModalInterface';

@Component
export default class InformationModal extends Vue implements ModalInterface {
  @Prop({ required: true })
  private readonly modalTitle!: string;

  private isOpen: boolean = false;

  public open(): void {
    this.isOpen = true;
  }

  public close(): void {
    this.isOpen = false;
  }
}
</script>
