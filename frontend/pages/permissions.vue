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
export default class Users extends Vue {
  private readonly headers = {
    id: 'Идентификатор',
    key: 'Ключ',
    title: 'Название',
    created_at: 'Создано в',
    updated_at: 'Обновлено в'
  };

  private readonly crudService: CrudService = new CrudService(this);

  public async mounted(): Promise<void> {
    await this.crudService.allRequest('/permission/all', this.$store, true);
  }
}
</script>
