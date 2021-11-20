<template>
  <ol v-if="list.length > 0" class="list-group side-bar-overflow">
    <li
      v-for="athlete in list"
      v-bind:key="athlete.id"
      class="list-group-item"
      @click="pushToCenter(athlete)"
      v-bind:style="{
        background:
          centerAthlete != null && centerAthlete.id == athlete.id
            ? '#969696'
            : '',
      }"
    >
      <p class="no-space">
        <span class="ml-1">
          <strong>
            {{ athlete.name }}
          </strong>
        </span>
        <span
          v-if="currentYear == -1"
          v-bind:style="{
            color: 'grey',
          }"
          class="float-right"
        >
          ({{ athlete.year }})
        </span>
        <span
          v-else
          v-bind:style="{
            color: athlete.gender == 'male' ? 'blue' : 'red',
          }"
          class="float-right"
        >
          {{ $t('general.gender_short_' + athlete.gender) }} |
          {{ athlete.requirements_tag }}
        </span>
      </p>
      <p class="no-space">
        <medal-display :medalScores="athlete.medal_scores" />
        <span
          class="float-right rightarrow"
          @click.stop="pushToCenter(athlete)"
        />
        <favourite-star-button v-bind="{ athlete: athlete }" />
      </p>
    </li>
  </ol>
  <ol v-else class="list-group">
    <li class="list-group-item">
      {{ $t('general.no_results') }}
    </li>
  </ol>
</template>

<script lang="ts">
import { mapGetters } from 'vuex';
import FavouriteStarButton from './FavouriteStarButton.vue';
import MedalDisplay from './MedalDisplay.vue';
import { PropType, defineComponent } from 'vue';
import { Athlete } from 'src/store/athletes/state';

export default defineComponent({
  components: { FavouriteStarButton, MedalDisplay },
  props: {
    list: { type: Array as PropType<Athlete[]>, default: [] as Athlete[] },
    currentYear: Number,
  },
  methods: {
    pushToCenter: function (athlete: Athlete) {
      void this.$store.dispatch('athletesModule/fetchAthlete', athlete.id);
    },
  },
  computed: {
    ...(mapGetters('athletesModule', {
      centerAthlete: 'athlete',
    }) as MapToComputed<{ centerAthlete: Athlete }>),
  },
});
</script>
