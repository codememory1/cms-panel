<template>
  <h1>Index page</h1>
</template>

<script lang="ts">
import { Component, Vue } from 'vue-property-decorator';

@Component({
  layout: 'AdminLayout',
  middleware: ['auth']
})
export default class Index extends Vue {}
</script>
