<template>
  <q-page class="row items-center justify-evenly">
    <favourites-list v-if="isLoggedIn"></favourites-list>
  </q-page>
</template>

<script lang="ts">
import { defineComponent } from 'vue';
import { mapState } from 'vuex';
import FavouritesList from '../components/Manager/FavouritesList.vue';

export default defineComponent({
  components: {
    FavouritesList,
  },
  computed: {
    ...mapState('authModule', {
      isLoggedIn: 'isLoggedIn',
    }),
  },
});
</script>
