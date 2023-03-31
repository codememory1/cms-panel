<template>
  <v-data-table
    :loading="loading"
    :search="search"
    :headers="collectHeaders"
    :items="entities"
    :sort-by="sortByKeys"
    :sort-desc="[true, false]"
    class="elevation-1"
    @click:row="$emit('clickByRow', $event)"
  >
    <template #top>
      <v-toolbar flat>
        <v-text-field
          v-model="search"
          append-icon="mdi-magnify"
          label="Поиск..."
          single-line
          hide-details
          class="ml-3"
        />
        <slot name="modals" />
      </v-toolbar>
    </template>
    <template #item.actions="{ item }">
      <slot name="actionsStart" :item="item" />
      <template v-if="showBaseActions">
        <v-icon small class="mr-2" @click.stop="editEntity(item)">mdi-pencil</v-icon>
        <v-icon small @click.stop="deleteEntity(item)">mdi-delete</v-icon>
      </template>
      <slot name="actionsEnd" :item="item" />
    </template>
    <template v-if="null !== error" #no-data>
      <span>{{ error }}</span>
    </template>
  </v-data-table>
</template>

<script lang="ts">
import { Component, Vue, Prop } from 'vue-property-decorator';

@Component
export default class BaseTable extends Vue {
  @Prop({ required: true })
  private readonly headers!: { [key: string]: string };

  @Prop({ required: true })
  private readonly entities!: Array<object>;

  @Prop({ required: true })
  private readonly sortByKeys!: Array<string>;

  @Prop({ required: false, default: true })
  private readonly showBaseActions!: boolean;

  @Prop({ required: false, default: null })
  private readonly error!: string | null;

  private search: string = '';
  private loading: boolean = false;

  private get collectHeaders(): Array<object> {
    const headerKeys = Object.keys(this.headers);
    const vuetifyHeaders: Array<object> = [];

    headerKeys.forEach((key) => {
      vuetifyHeaders.push({
        text: this.headers[key],
        value: key
      });
    });

    return vuetifyHeaders;
  }

  private editEntity(entity: object): void {
    this.$emit('openEdit', entity);
  }

  private deleteEntity(entity: object): void {
    this.$emit('openDelete', entity);
  }

  public setIsLoading(is: boolean): void {
    this.loading = is;
  }
}
</script>
