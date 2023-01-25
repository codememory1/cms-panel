<template>
  <BaseTable
    ref="table"
    :error="crudService.getErrors().atAll"
    :show-base-actions="false"
    :headers="headers"
    :entities="crudService.getEntities()"
    :sort-by-keys="['created_at', 'updated_at']"
  >
    <template #modals>
      <InformationModal ref="infoModal" modal-title="Payload действия">
        <pre>{{ getPayload }}</pre>
      </InformationModal>
    </template>
    <template #actionsStart="{ item }">
      <v-icon small class="mr-2" @click="openInfoModal(item)">mdi-eye</v-icon>
    </template>
  </BaseTable>
</template>

<script lang="ts">
import { Component, Vue } from 'vue-property-decorator';
import BaseTable from '~/components/Table/BaseTable.vue';
import CrudService from '~/services/crud-service';
import InformationModal from '~/components/Modal/InformationModal.vue';

@Component({
  layout: 'AdminLayout',
  middleware: ['auth'],
  components: {
    BaseTable,
    InformationModal
  }
})
export default class Users extends Vue {
  private readonly headers = {
    'executor.id': 'Идентификатор исполнителя',
    'executor.name': 'Имя исполнителя',
    entity: 'Изменяемая сущность',
    action: 'Действие',
    created_at: 'Создано в',
    actions: 'Действия'
  };

  private readonly crudService: CrudService = new CrudService(this);

  public async mounted(): Promise<void> {
    await this.crudService.allRequest('/action-log/all', this.$store, true);
  }

  private openInfoModal(entity: object): void {
    this.crudService.openEditableModal(entity, this.$refs.infoModal as InformationModal);
  }

  private get getPayload(): string {
    if (this.crudService.getEditedEntity() !== undefined) {
      return JSON.stringify(this.crudService.getEditedEntity().payload, null, 2);
    }

    return '';
  }
}
</script>
