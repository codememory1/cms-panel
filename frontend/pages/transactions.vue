<template>
  <v-flex>
    <BaseTable
      ref="table"
      :error="crudService.getErrors().atAll"
      :headers="headers"
      :entities="crudService.getEntities()"
      :sort-by-keys="['created_at', 'updated_at']"
    />
    <v-pagination
      v-model="page"
      :length="pagination.numberPages"
      :total-visible="7"
      @input="onChangePage"
    />
  </v-flex>
</template>

<script lang="ts">
import { Component, Vue } from 'vue-property-decorator';
import BaseTable from '~/components/Table/BaseTable.vue';
import CrudService from '~/services/crud-service';

@Component({
  layout: 'AdminLayout',
  middleware: ['auth'],
  components: {
    BaseTable
  }
})
export default class Transactions extends Vue {
  private page: number = 1;
  private pagination = {
    numberPages: 0
  };

  private readonly headers = {
    id: 'Идентификатор',
    type: 'Тип',
    'phone.id': 'Идентификатор телефона',
    'card.name': 'Имя карточки',
    'card.number': 'Последние цифры карты',
    completed_on_time: 'Время завершения',
    sum: 'Сумма',
    created_at: 'Создано в'
  };

  private readonly crudService: CrudService = new CrudService(this);

  public async mounted(): Promise<void> {
    await this.crudService.allRequest(
      '/transaction/all?pagination[page]=1&pagination[limit]=20',
      this.$store,
      true,
      (response) => {
        this.pagination.numberPages = response.meta.pagination.total_pages;
      }
    );
  }

  private async onChangePage(page: number): Promise<void> {
    await this.crudService.allRequest(
      `/transaction/all?pagination[page]=${page}&pagination[limit]=20`,
      this.$store,
      true
    );
  }
}
</script>
