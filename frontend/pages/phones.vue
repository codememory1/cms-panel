<template>
  <BaseTable
    ref="table"
    :error="crudService.getErrors().atAll"
    :headers="headers"
    :entities="crudService.getEntities()"
    :sort-by-keys="['created_at', 'updated_at']"
    @openDelete="openDeleteModal"
    @openEdit="openEditModal"
    @clickByRow="clickByRow"
  >
    <template #modals>
      <PhoneTransactionModal
        ref="phoneTransactionModal"
        :is-loading="isLoadingPhoneTransaction"
        :number-pages="phoneTransactionTotalPages"
        :data="phoneTransactionData"
        @changePage="phoneTransactionChangePage"
      />
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
import PhoneTransactionModal from '~/components/Modal/PhoneTransactionModal.vue';
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
    DeleteModal,
    PhoneTransactionModal
  }
})
export default class Phones extends Vue {
  private readonly headers = {
    id: 'Идентификатор',
    'number.country_code': 'Код страны',
    'number.number': 'Телефон',
    sum_enrollment: 'Зачислений на сумму',
    sum_transfer: 'Переводов на сумму',
    sum_payment: 'Оплат на сумму',
    sum_purchase: 'Покупок на сумму',
    'balance.balance': 'Баланс',
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

  private isLoadingPhoneTransaction: boolean = true;
  private phoneTransactionData: object | null = null;
  private phoneTransactionTotalPages: number = 1;
  private showTransactionByPhone: any = null;

  private readonly crudService: CrudService = new CrudService(this);
  private readonly phoneTransactionService: CrudService = new CrudService(this);

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

  private async clickByRow(item: any): Promise<void> {
    this.showTransactionByPhone = item;
    (this.$refs as any).phoneTransactionModal.open();

    await this.phoneTransactionService.allRequest(
      `/transaction/${item.id}/all?pagination[page]=1&pagination[limit]=20`,
      this.$store,
      true,
      (response) => {
        this.phoneTransactionTotalPages = response.meta.pagination.total_pages;
      }
    );

    this.phoneTransactionData = this.phoneTransactionService.getEntities();

    this.isLoadingPhoneTransaction = false;
  }

  private async phoneTransactionChangePage(page: number): Promise<void> {
    this.isLoadingPhoneTransaction = true;

    await this.phoneTransactionService.allRequest(
      `/transaction/${this.showTransactionByPhone.id}/all?pagination[page]=${page}&pagination[limit]=20`,
      this.$store,
      true,
      (response) => {
        this.phoneTransactionTotalPages = response.meta.pagination.total_pages;
      }
    );

    this.phoneTransactionData = this.phoneTransactionService.getEntities();

    this.isLoadingPhoneTransaction = false;
  }
}
</script>
