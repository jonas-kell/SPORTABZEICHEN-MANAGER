<template>
  <div class="row justify-content-center no-gutters">
    <div class="col-md-4 col-12">
      <div class="row justify-content-center mr-3 ml-3 mb-2">
        <sidebar />
      </div>
    </div>
    <div class="col-md-8 col-12">
      <div class="row justify-content-center mr-3 ml-3">
        <center />
      </div>
    </div>
  </div>
</template>

<script lang="ts">
import Center from './Center.vue';
import Sidebar from './Sidebar.vue';
import { defineComponent } from 'vue';

export default defineComponent({
  components: { Center, Sidebar },
});
</script>
