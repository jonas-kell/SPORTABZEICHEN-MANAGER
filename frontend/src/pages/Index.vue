<template>
  <q-page class="row items-center justify-evenly">
    <p v-if="!isLoggedIn" class="text-center">
      Nicht eingeloggt. Bitte melde dich an
      <br />
      <q-btn
        class="q-mt-sm"
        color="primary"
        icon-right="account_circle"
        label="Anmelden"
        type="a"
        href="#/auth"
      ></q-btn>
    </p>
    <manager-index v-else></manager-index>
  </q-page>
</template>

<script lang="ts">
import { defineComponent } from 'vue';
import { mapState } from 'vuex';
import ManagerIndex from './../components/Manager/ManagerIndex.vue';

export default defineComponent({
  components: {
    ManagerIndex,
  },
  computed: {
    ...mapState('authModule', {
      isLoggedIn: 'isLoggedIn',
    }),
  },
});
</script>
