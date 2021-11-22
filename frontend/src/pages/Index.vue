<template>
  <q-page v-if="!isLoggedIn" class="row items-center justify-evenly">
    <p class="text-center">
      {{ $t('auth.pleaseEnroll') }}
      <br />
      <q-btn
        class="q-mt-sm"
        color="primary"
        icon-right="account_circle"
        :label="$t('auth.enroll')"
        type="a"
        href="#/auth"
      ></q-btn>
    </p>
  </q-page>
  <manager-center class="col-12" v-else></manager-center>
</template>

<script lang="ts">
import { defineComponent } from 'vue';
import { mapState } from 'vuex';
import ManagerCenter from './../components/Manager/Center.vue';

export default defineComponent({
  components: {
    ManagerCenter,
  },
  computed: {
    ...mapState('authModule', {
      isLoggedIn: 'isLoggedIn',
    }),
  },
});
</script>
