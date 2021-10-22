<template>
  <span
    class="float-right favourite_star"
    v-bind:class="athlete.favourite ? 'active' : 'inactive'"
    @click.stop="toggleFavourite(athlete)"
  />
</template>

<script lang="ts">
import { PropType, defineComponent } from 'vue';
import { Athlete } from 'src/store/athletes/state';

export default defineComponent({
  props: {
    athlete: {
      type: Object as PropType<Athlete>,
      default: {} as Athlete,
    },
  },
  methods: {
    toggleFavourite: function (athlete: Athlete) {
      let startState = athlete.favourite;

      if (startState) {
        // drop favourite
        void this.$store.dispatch('athletesModule/dropFavourite', athlete);
      } else {
        // associate favourite
        void this.$store.dispatch('athletesModule/addFavourite', athlete);
      }

      // update own state immediately
      athlete.favourite = !startState;
    },
  },
});
</script>
