<template>
  <q-page class="row items-center justify-evenly">
    <p v-if="!isLoggedIn" class="text-center">
      Nicht eingeloggt. Bitte melde dich an
      <br />
      <q-btn
        class="q-mt-sm "
        color="primary"
        icon-right="account_circle"
        label="Anmelden"
        type="a"
        href="#/auth"
      ></q-btn>
    </p>
  </q-page>
</template>

<script lang="ts">
import { defineComponent } from 'vue';
import { mapState } from 'vuex';

export default defineComponent({
  computed: {
    ...mapState('authModule', {
      isLoggedIn: 'isLoggedIn'
    })
  }
});
</script>
