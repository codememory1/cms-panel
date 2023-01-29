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
        modal-title="Редактирование выражений"
        @close="crudService.closeEditableModal()"
        @update="updateEntity"
      >
        <v-textarea
          v-model="inputData.transfer.value"
          :error="inputData.transfer.error"
          label="Выражения перевода"
        />
        <v-textarea
          v-model="inputData.enrollment.value"
          :error="inputData.enrollment.error"
          label="Выражения зачисления"
        />
        <v-textarea
          v-model="inputData.payment.value"
          :error="inputData.payment.error"
          label="Выражения оплаты"
        />
        <v-textarea
          v-model="inputData.purchase.value"
          :error="inputData.purchase.error"
          label="Выражения покупки"
        />
      </UpdateModal>
      <CreateModal
        ref="createModal"
        button-title="Добавить выражение"
        modal-title="Добавление выражения"
        @create="createEntity"
      >
        <v-autocomplete
          v-model="createInputData.bank.value"
          :error-messages="banksCrudService.getErrors().atAll"
          :error="banksCrudService.getErrors().atAll !== null || createInputData.bank.error"
          :items="banksCrudService.getEntities()"
          label="Укажите банк"
          item-text="title"
          item-value="id"
          dense
        />
        <v-textarea
          v-model="createInputData.transfer.value"
          :error="createInputData.transfer.error"
          label="Выражения перевода"
        />
        <v-textarea
          v-model="createInputData.enrollment.value"
          :error="createInputData.enrollment.error"
          label="Выражения зачисления"
        />
        <v-textarea
          v-model="createInputData.payment.value"
          :error="createInputData.payment.error"
          label="Выражения оплаты"
        />
        <v-textarea
          v-model="createInputData.purchase.value"
          :error="createInputData.purchase.error"
          label="Выражения покупки"
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
export default class BankExpressions extends Vue {
  private readonly headers = {
    'bank.title': 'Название банка',
    transfer: 'Перевод',
    enrollment: 'Зачисление',
    payment: 'Оплата',
    purchase: 'Покупка',
    created_at: 'Создано в',
    updated_at: 'Обновлено в',
    actions: 'Действия'
  };

  private createInputData = {
    bank: {
      error: false,
      value: null
    },
    transfer: {
      error: false,
      value: '[]'
    },
    enrollment: {
      error: false,
      value: '[]'
    },
    payment: {
      error: false,
      value: '[]'
    },
    purchase: {
      error: false,
      value: '[]'
    }
  };

  private inputData = {
    transfer: {
      error: false,
      value: '[]'
    },
    enrollment: {
      error: false,
      value: '[]'
    },
    payment: {
      error: false,
      value: '[]'
    },
    purchase: {
      error: false,
      value: '[]'
    }
  };

  private readonly crudService: CrudService = new CrudService(this);
  private readonly banksCrudService: CrudService = new CrudService(this);

  public async mounted(): Promise<void> {
    await this.crudService.allRequest('/bank/expression/all', this.$store, true);
    await this.banksCrudService.allRequest('/bank/all', this.$store);
  }

  private openDeleteModal(entity: object): void {
    this.crudService.openDeleteModal(entity);
  }

  private openEditModal(entity: object): void {
    this.crudService.openEditModal(entity, this.inputData, (entity: any) => {
      this.inputData.transfer.value = JSON.stringify(entity.transfer);
      this.inputData.enrollment.value = JSON.stringify(entity.enrollment);
      this.inputData.payment.value = JSON.stringify(entity.payment);
      this.inputData.purchase.value = JSON.stringify(entity.purchase);
    });
  }

  private deleteEntity(): void {
    this.crudService.deleteRequest(
      `/bank/${this.crudService.getEditedEntity().id}/expression/delete`
    );
  }

  private async createEntity(): Promise<void> {
    await this.crudService.createRequest('/bank/expression/create', this.createInputData);
  }

  private async updateEntity(): Promise<void> {
    await this.crudService.editRequest(
      `/bank/${this.crudService.getEditedEntity().bank.id}/expression/edit`,
      this.inputData
    );
  }
}
</script>
