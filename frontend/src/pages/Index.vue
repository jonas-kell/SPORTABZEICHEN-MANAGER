<template>
  <q-page class="row items-center justify-evenly">
    <p v-if="!isLoggedIn" class="text-center">
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
    <manager-center v-else></manager-center>
  </q-page>
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
