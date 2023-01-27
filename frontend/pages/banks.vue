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
        modal-title="Редактирование банка"
        @close="crudService.closeEditableModal()"
        @update="updateEntity"
      >
        <v-text-field
          v-model="inputData.title.value"
          :error="inputData.title.error"
          label="Номер телефона"
        />
        <v-text-field
          v-model="inputData.number.value"
          :error="inputData.number.error"
          label="Номер банка"
        />
      </UpdateModal>
      <CreateModal
        ref="createModal"
        button-title="Добавить банк"
        modal-title="Добавление банка"
        @create="createEntity"
      >
        <v-text-field
          v-model="inputData.title.value"
          :error="inputData.title.error"
          label="Название банка"
        />
        <v-text-field
          v-model="inputData.number.value"
          :error="inputData.number.error"
          label="Номер банка"
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
export default class Banks extends Vue {
  private readonly headers = {
    id: 'Идентификатор',
    title: 'Название банка',
    number: 'Номер банка',
    created_at: 'Создано в',
    updated_at: 'Обновлено в',
    actions: 'Действия'
  };

  private inputData = {
    title: {
      error: false,
      value: null
    },
    number: {
      error: false,
      value: null
    }
  };

  private readonly crudService: CrudService = new CrudService(this);

  public async mounted(): Promise<void> {
    await this.crudService.allRequest('/bank/all', this.$store, true);
  }

  private openDeleteModal(entity: object): void {
    this.crudService.openDeleteModal(entity);
  }

  private openEditModal(entity: object): void {
    this.crudService.openEditModal(entity, this.inputData, (entity: any) => {
      this.inputData.title.value = entity.title;
      this.inputData.number.value = entity.number;
    });
  }

  private deleteEntity(): void {
    this.crudService.deleteRequest(`/bank/${this.crudService.getEditedEntity().id}/delete`);
  }

  private async createEntity(): Promise<void> {
    await this.crudService.createRequest('/bank/create', this.inputData);
  }

  private async updateEntity(): Promise<void> {
    await this.crudService.editRequest(
      `/bank/${this.crudService.getEditedEntity().id}/edit`,
      this.inputData
    );
  }
}
</script>
