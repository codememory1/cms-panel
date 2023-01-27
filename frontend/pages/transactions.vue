<template>
  <BaseTable
    ref="table"
    :error="crudService.getErrors().atAll"
    :headers="headers"
    :entities="crudService.getEntities()"
    :sort-by-keys="['created_at', 'updated_at']"
  />
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
  private readonly headers = {
    id: 'Идентификатор',
    type: 'Тип',
    'card.name': 'Имя карточки',
    'card.number': 'Последние цифры карты',
    completed_on_time: 'Время завершения',
    sum: 'Сумма',
    created_at: 'Создано в'
  };

  private readonly crudService: CrudService = new CrudService(this);

  public async mounted(): Promise<void> {
    await this.crudService.allRequest('/transaction/all', this.$store, true);
  }
}
</script>
