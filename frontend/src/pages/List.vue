<template>
  <q-page class="row items-center justify-evenly">
    <list-index v-if="isLoggedIn"></list-index>
  </q-page>
</template>

<script lang="ts">
import { defineComponent } from 'vue';
import { mapState } from 'vuex';
import ListIndex from '../components/List/ListIndex.vue';

export default defineComponent({
  components: {
    ListIndex,
  },
  computed: {
    ...mapState('authModule', {
      isLoggedIn: 'isLoggedIn',
    }),
  },
});
</script>
