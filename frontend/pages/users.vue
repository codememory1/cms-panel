<template>
  <BaseTable
    ref="table"
    :error="usersCrudService.getErrors().atAll"
    :headers="headers"
    :entities="usersCrudService.getEntities()"
    :sort-by-keys="['created_at', 'updated_at']"
    @openDelete="openDeleteModal"
    @openEdit="openEditModal"
  >
    <template #modals>
      <UpdateModal
        ref="editModal"
        modal-title="Редактирование пользователя"
        @close="usersCrudService.closeEditableModal()"
        @update="updateEntity"
      >
        <v-text-field
          v-model="inputData.name.value"
          :error="inputData.name.error"
          :label="headers.name"
        />
        <v-text-field
          v-model="inputData.email.value"
          :error="inputData.email.error"
          :label="headers.email"
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
        <v-select
          v-model="inputData.role.value"
          :error-messages="rolesCrudService.getErrors().atAll"
          :error="rolesCrudService.getErrors().atAll !== null || inputData.role.error"
          :items="rolesCrudService.getEntities()"
          label="Укажите роль"
          item-text="title"
          item-value="id"
          dense
        />
      </UpdateModal>
      <UpdateModal
        ref="editPasswordModal"
        modal-title="Редактирование пароля пользователя"
        @close="usersCrudService.closeEditableModal()"
        @update="updatePassword"
      >
        <v-text-field
          v-model="inputPasswordData.password.value"
          type="password"
          :error="inputPasswordData.password.error"
          label="Пароль"
        />
      </UpdateModal>
      <CreateModal
        ref="createModal"
        button-title="Создать пользователя"
        modal-title="Создание пользователя"
        @create="createEntity"
      >
        <v-text-field
          v-model="inputData.name.value"
          :error="inputData.name.error"
          :label="headers.name"
        />
        <v-text-field
          v-model="inputData.email.value"
          :error="inputData.email.error"
          :label="headers.email"
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
        <v-select
          v-model="inputData.role.value"
          :error-messages="rolesCrudService.getErrors().atAll"
          :error="rolesCrudService.getErrors().atAll !== null || inputData.role.error"
          :items="rolesCrudService.getEntities()"
          label="Укажите роль"
          item-text="title"
          item-value="id"
          dense
        />
      </CreateModal>
      <DeleteModal
        ref="deleteModal"
        modal-title="Удаление пользователя"
        @delete="deleteEntity"
        @close="usersCrudService.closeDeleteModal()"
      />
    </template>
    <template #actionsStart="{ item }">
      <v-icon small class="mr-2" @click="openEditPasswordModal(item)">mdi-key</v-icon>
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
export default class Users extends Vue {
  private readonly headers = {
    id: 'Идентификатор',
    name: 'Имя',
    email: 'Почта',
    status: 'Статус',
    role_name: 'Роль',
    created_at: 'Создано в',
    updated_at: 'Обновлено в',
    actions: 'Действия'
  };

  private readonly statuses = [
    { text: 'Активирован', value: 'ACTIVATED' },
    { text: 'Заблокирован', value: 'BLOCKED' }
  ];

  private inputData = {
    name: {
      error: false,
      value: null
    },
    email: {
      error: false,
      value: null
    },
    status: {
      error: false,
      value: null
    },
    role: {
      error: false,
      value: null
    }
  };

  private inputPasswordData = {
    password: {
      error: false,
      value: null
    }
  };

  private readonly usersCrudService: CrudService = new CrudService(this);
  private readonly rolesCrudService: CrudService = new CrudService(this);

  public async mounted(): Promise<void> {
    await this.usersCrudService.allRequest('/user/all', this.$store, true);
    await this.rolesCrudService.allRequest('/role/all', this.$store);
  }

  private openDeleteModal(entity: object): void {
    this.usersCrudService.openDeleteModal(entity);
  }

  private openEditModal(entity: object): void {
    this.usersCrudService.openEditModal(entity, this.inputData, (entity: any) => {
      this.inputData.name.value = entity.name;
      this.inputData.email.value = entity.email;
      this.inputData.status.value = entity.status;
      this.inputData.role.value = entity.role.id;
    });
  }

  private deleteEntity(): void {
    this.usersCrudService.deleteRequest(
      `/user/${this.usersCrudService.getEditedEntity().id}/delete`
    );
  }

  private async createEntity(): Promise<void> {
    await this.usersCrudService.createRequest('/user/create', this.inputData);
  }

  private async updateEntity(): Promise<void> {
    await this.usersCrudService.editRequest(
      `/user/${this.usersCrudService.getEditedEntity().id}/edit`,
      this.inputData
    );
  }

  private async updatePassword(): Promise<void> {
    await this.usersCrudService.editRequest(
      `user/${this.usersCrudService.getEditedEntity().id}/password/edit`,
      this.inputPasswordData,
      this.$refs.editPasswordModal as UpdateModal
    );
  }

  private openEditPasswordModal(entity: object): void {
    this.usersCrudService.openEditableModal(entity, this.$refs.editPasswordModal as UpdateModal);
  }
}
</script>
