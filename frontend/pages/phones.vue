<template>
  <BaseTable
    ref="table"
    :error="crudService.getErrors().atAll"
    :headers="headers"
    :entities="crudService.getEntities()"
    :sort-by-keys="['created_at', 'updated_at']"
    @openDelete="openDeleteModal"
    @openEdit="openEditModal"
  >
    <template #modals>
      <UpdateModal
        ref="editModal"
        modal-title="Редактирование телефона"
        @close="crudService.closeEditableModal()"
        @update="updateEntity"
      >
        <v-text-field
          v-model="inputData.number.value"
          :error="inputData.number.error"
          label="Номер телефона"
        />
        <v-select
          v-model="inputData.status.value"
          :error="inputData.status.error"
          :items="statuses"
          item-text="text"
          item-value="value"
          label="Укажите статус"
          dense
        />
      </UpdateModal>
      <CreateModal
        ref="createModal"
        button-title="Создать телефон"
        modal-title="Создание телефона"
        @create="createEntity"
      >
        <v-text-field
          v-model="inputData.number.value"
          :error="inputData.number.error"
          label="Номер телефона"
        />
        <v-select
          v-model="inputData.status.value"
          :error="inputData.status.error"
          :items="statuses"
          item-text="text"
          item-value="value"
          label="Укажите статус"
          dense
        />
      </CreateModal>
      <DeleteModal
        ref="deleteModal"
        modal-title="Удаление телефона"
        @delete="deleteEntity"
        @close="crudService.closeDeleteModal()"
      />
    </template>
  </BaseTable>
</template>

<script lang="ts">
import { Component, Vue } from 'vue-property-decorator';
import BaseTable from '~/components/Table/BaseTable.vue';
import CreateModal from '~/components/Modal/CreateModal.vue';
import UpdateModal from '~/components/Modal/UpdateModal.vue';
import DeleteModal from '~/components/Modal/DeleteModal.vue';
import CrudService from '~/services/crud-service';

type InputDataType = {
  number: {
    error: boolean;
    value: string | null;
  };

  status: {
    error: boolean;
    value: string | null;
  };
};

@Component({
  layout: 'AdminLayout',
  middleware: ['auth'],
  components: {
    BaseTable,
    CreateModal,
    UpdateModal,
    DeleteModal
  }
})
export default class Phones extends Vue {
  private readonly headers = {
    id: 'Идентификатор',
    'number.country_code': 'Код страны',
    'number.number': 'Телефон',
    status: 'Статус',
    created_at: 'Создано в',
    updated_at: 'Обновлено в',
    actions: 'Действия'
  };

  private readonly statuses = [
    { text: 'Доступный', value: 'ALLOWED' },
    { text: 'Недоступный', value: 'NOT_ALLOWED' }
  ];

  private inputData: InputDataType = {
    number: {
      error: false,
      value: null
    },
    status: {
      error: false,
      value: null
    }
  };

  private readonly crudService: CrudService = new CrudService(this);

  public async mounted(): Promise<void> {
    await this.crudService.allRequest('/phone/all', this.$store, true);
  }

  private openDeleteModal(entity: object): void {
    this.crudService.openDeleteModal(entity);
  }

  private openEditModal(entity: object): void {
    this.crudService.openEditModal(entity, this.inputData, (entity: any) => {
      this.inputData.number.value = `+${entity.number.country_code + entity.number.number}`;
      this.inputData.status.value = entity.status;
    });
  }

  private deleteEntity(): void {
    this.crudService.deleteRequest(`/phone/${this.crudService.getEditedEntity().id}/delete`);
  }

  private async createEntity(): Promise<void> {
    await this.crudService.createRequest('/phone/create', this.inputData);
  }

  private async updateEntity(): Promise<void> {
    await this.crudService.editRequest(
      `/phone/${this.crudService.getEditedEntity().id}/edit`,
      this.inputData
    );
  }
}
</script>
