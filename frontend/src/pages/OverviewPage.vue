<template>
  <q-page class="row q-pa-lg">
    <overview-list v-if="isLoggedIn"></overview-list>
  </q-page>
</template>

<script lang="ts">
import { defineComponent } from 'vue';
import { mapState } from 'vuex';
import OverviewList from '../components/Manager/OverviewList.vue';

export default defineComponent({
  components: {
    OverviewList,
  },
  computed: {
    ...mapState('authModule', {
      isLoggedIn: 'isLoggedIn',
    }),
  },
});
</script>
