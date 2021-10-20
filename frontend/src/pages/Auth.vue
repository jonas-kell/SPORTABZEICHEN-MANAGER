<template>
  <q-page class="row items-center justify-evenly">
    Auth here
  </q-page>
</template>

<script lang="ts">
import { defineComponent } from 'vue';

export default defineComponent({
  created: function() {
    this.$axios
      .get('sanctum/csrf-cookie')
      .then(response => {
        console.log(response);
      })
      .catch(err => console.error(err));
  }
});
</script>
