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
        modal-title="Редактирование роли"
        @close="crudService.closeEditableModal()"
        @update="updateEntity"
      >
        <v-text-field
          v-model="inputData.title.value"
          :error="inputData.title.error"
          label="Название"
        />
        <v-autocomplete
          v-model="inputData.permissions.value"
          :error-messages="permissionsCrudService.getErrors().atAll"
          :error="permissionsCrudService.getErrors().atAll !== null || inputData.permissions.error"
          :items="permissionsCrudService.getEntities()"
          label="Укажите разрешения"
          item-text="title"
          item-value="key"
          multiple
          dense
        />
      </UpdateModal>
      <CreateModal
        ref="createModal"
        button-title="Создать роль"
        modal-title="Создание роли"
        @create="createEntity"
      >
        <v-text-field
          v-model="inputData.title.value"
          :error="inputData.title.error"
          label="Название"
        />
        <v-autocomplete
          v-model="inputData.permissions.value"
          :error-messages="permissionsCrudService.getErrors().atAll"
          :error="permissionsCrudService.getErrors().atAll !== null || inputData.permissions.error"
          :items="permissionsCrudService.getEntities()"
          label="Укажите разрешения"
          item-text="title"
          item-value="key"
          multiple
          dense
        />
      </CreateModal>
      <DeleteModal
        ref="deleteModal"
        modal-title="Удаление роли"
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
export default class Roles extends Vue {
  private readonly headers = {
    id: 'Идентификатор',
    title: 'Название',
    created_at: 'Создано в',
    updated_at: 'Обновлено в',
    actions: 'Действия'
  };

  private inputData = {
    title: {
      error: false,
      value: null
    },
    permissions: {
      error: false,
      value: []
    }
  };

  private readonly crudService: CrudService = new CrudService(this);
  private readonly permissionsCrudService: CrudService = new CrudService(this);

  public async mounted(): Promise<void> {
    await this.crudService.allRequest('/role/all', this.$store, true);
    await this.permissionsCrudService.allRequest('/permission/all', this.$store);
  }

  private openDeleteModal(entity: object): void {
    this.crudService.openDeleteModal(entity);
  }

  private openEditModal(entity: object): void {
    this.crudService.openEditModal(entity, this.inputData, (entity: any) => {
      this.inputData.title.value = entity.title;
      this.inputData.permissions.value = entity.permissions.map((permission: any) => {
        return permission.permission.key;
      });
    });
  }

  private deleteEntity(): void {
    this.crudService.deleteRequest(`/role/${this.crudService.getEditedEntity().id}/delete`);
  }

  private async createEntity(): Promise<void> {
    await this.crudService.createRequest('/role/create', this.inputData);
  }

  private async updateEntity(): Promise<void> {
    await this.crudService.editRequest(
      `/role/${this.crudService.getEditedEntity().id}/edit`,
      this.inputData
    );
  }
}
</script>
