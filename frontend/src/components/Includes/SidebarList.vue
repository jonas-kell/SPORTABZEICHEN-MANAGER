<style scoped>
li.list-group-item {
  border: 1px solid rgb(101 101 101 / 51%);
  padding: 0.25rem 0.75rem;
}

p.no-space {
  padding-top: 0;
  padding-bottom: 0;
  margin-top: 0;
  margin-bottom: 0;
}

.list-group {
  display: -webkit-box;
  display: -ms-flexbox;
  display: flex;
  -webkit-box-orient: vertical;
  -webkit-box-direction: normal;
  -ms-flex-direction: column;
  flex-direction: column;
  padding: 0;
  margin: 0;
}

.list-group-item:first-child {
  border-top-left-radius: 0.25rem;
  border-top-right-radius: 0.25rem;
}

.list-group-item:last-child {
  margin-bottom: 0;
  border-bottom-right-radius: 0.25rem;
  border-bottom-left-radius: 0.25rem;
}

.list-group-item {
  position: relative;
  display: block;
  padding: 0.75rem 1.25rem;
  margin-bottom: -1px;
  background-color: #fff;
  border: 1px solid rgba(0, 0, 0, 0.125);
}
</style>

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
        >
          <q-tooltip
            anchor="top middle"
            self="bottom middle"
            max-width="40rem"
            :offset="[10, 10]"
          >
            {{ $t('general.showCenter') }}
          </q-tooltip>
        </span>
        <favourite-star-button v-bind="{ athlete: athlete }" />
        <span
          v-if="currentYear == -1"
          class="float-right clone-button"
          @click.stop="setupCloneAthlete(athlete)"
        >
          <q-tooltip
            anchor="top middle"
            self="bottom middle"
            max-width="40rem"
            :offset="[10, 10]"
          >
            {{ $t('general.cloneForYear') }}
          </q-tooltip>
        </span>
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
import { mapActions, mapGetters } from 'vuex';
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

      if (this.$router.currentRoute.value.fullPath != '/') {
        console.log('Info: redirect');
        void this.$router.push('/');
      }
    },
    ...(mapActions('athletesModule', {
      mappedCreateAthlete: 'createAthlete',
    }) as unknown as {
      mappedCreateAthlete: (params: {
        athlete: Athlete;
        cloned: boolean;
      }) => string;
    }),
    setupCloneAthlete: async function (athlete: Athlete) {
      let hasCompletedTheBadge = athlete.hasFinished;

      let newNumberBadges = hasCompletedTheBadge
        ? athlete.numberOfBadgesSoFar + 1
        : athlete.numberOfBadgesSoFar;

      let currentYear = new Date().getFullYear();

      if (athlete.birthday) {
        let parsedBirthDate = new Date(athlete.birthday);
        if (!isNaN(parsedBirthDate as unknown as number)) {
          // has date
          const birthYear = parsedBirthDate.getFullYear();

          const spzAgePrev = athlete.year - birthYear;
          const spzAgeNew = currentYear - birthYear;

          if (spzAgePrev < 18 && spzAgeNew >= 18) {
            newNumberBadges = 0; // overwrite badge number to 0 for new adult
          }
        }
      }

      let clonedAthlete = {
        name: athlete.name,
        year: currentYear,
        birthday: athlete.birthday,
        gender: athlete.gender,
        proofOfSwimming: athlete.proofOfSwimming,
        lastBadgeYear: hasCompletedTheBadge
          ? athlete.year
          : athlete.lastBadgeYear,
        numberOfBadgesSoFar: newNumberBadges,
        lastYearIdentNo: athlete.newIdentNo ? athlete.newIdentNo : undefined,
      } as Athlete;

      this.mappedCreateAthlete({
        athlete: clonedAthlete,
        cloned: true,
      });
    },
  },
  computed: {
    ...(mapGetters('athletesModule', {
      centerAthlete: 'athlete',
    }) as MapToComputed<{ centerAthlete: Athlete }>),
  },
});
</script>
