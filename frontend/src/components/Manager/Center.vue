<style scoped>
.hide-overflow {
  overflow: hidden;
}

.no-break {
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
}
</style>

<template>
  <q-page
    v-if="newAthlete == null && athlete == null"
    class="row items-center justify-evenly"
  >
    <div class="text-center">
      <h3>
        {{ $t('general.welcome') }}
      </h3>
      <p>
        {{ $t('general.action') }}
        <br />
        {{ $t('general.sidebarHint') }}
      </p>
    </div>
  </q-page>
  <q-page v-else-if="athlete != null" class="row q-pa-lg">
    <!-- NORMAL ATHLETE MANAGEMENT PART -->
    <q-card class="col-12" bordered>
      <q-card-section class="bg-primary text-white row">
        <b class="col-6">
          {{ modifyableAthlete.name }}
          ({{ $t('general.' + modifyableAthlete.gender) }} |
          {{ modifyableAthlete.requirements_tag }})
        </b>
        <div class="col-6 text-right">
          <q-btn
            class="float-right"
            size="sm"
            :color="canEdit ? 'positive' : 'primary'"
            :label="canEdit ? $t('general.save') : $t('general.edit')"
            v-on:click="toggleEdit"
          ></q-btn>
          <favourite-star-button
            class="q-mr-md"
            v-bind="{ athlete: modifyableAthlete }"
          />
        </div>
      </q-card-section>

      <q-separator></q-separator>

      <q-card-section class="row">
        <div class="col-12 col-lg-6 q-px-md q-pb-none row">
          <q-input
            filled
            v-model="modifyableAthlete.name"
            :label="$t('general.name')"
            class="col-12"
            id="center_name_field"
            name="center_name_field"
            lazy-rules
            :rules="[
              (val) => (val && val.length > 0) || $t('validation.notEmpty'),
            ]"
            :disable="!canEdit ? true : null"
          ></q-input>
        </div>
        <div class="col-12 col-lg-6 q-px-md q-pb-none row">
          <q-input
            filled
            v-model="modifyableAthlete.year"
            :label="$t('general.year')"
            class="col-12"
            id="center_year_field"
            name="center_year_field"
            lazy-rules
            mask="####"
            :rules="[
              (val) => (val && val.length > 0) || $t('validation.notEmpty'),
            ]"
            :disable="!canEdit ? true : null"
          ></q-input>
        </div>
        <div class="col-12 col-lg-6 q-px-md q-pb-none row">
          <q-input
            filled
            class="col-12"
            v-model="modifyableAthlete.birthday"
            :label="$t('general.birthday')"
            mask="date"
            :rules="[
              (val) =>
                !(val && val.length > 0) ||
                /^-?[\d]+\/[0-1]\d\/[0-3]\d$/.test(val) ||
                $t('validation.date'),
            ]"
            id="center_birthday_field"
            name="center_birthday_field"
            :disable="!canEdit ? true : null"
          >
            <template v-slot:append>
              <q-icon name="event" class="cursor-pointer">
                <q-popup-proxy
                  ref="qDateProxy"
                  cover
                  transition-show="scale"
                  transition-hide="scale"
                >
                  <q-date v-model="modifyableAthlete.birthday">
                    <div class="row items-center justify-end">
                      <q-btn
                        v-close-popup
                        label="Close"
                        color="primary"
                        flat
                      ></q-btn>
                    </div>
                  </q-date>
                </q-popup-proxy>
              </q-icon>
            </template>
          </q-input>
        </div>
        <div class="col-12 col-lg-6 q-px-md q-pb-none row">
          <q-select
            filled
            class="col-12"
            v-model="modifyableAthlete.gender"
            emit-value
            map-options
            :label="$t('general.years')"
            :options="[
              { value: 'female', label: $t('general.female') },
              { value: 'male', label: $t('general.male') },
            ]"
            id="center_gender_field"
            name="center_gender_field"
            :disable="!canEdit ? true : null"
          ></q-select>
        </div>
      </q-card-section>

      <q-separator></q-separator>

      <q-card-section class="row">
        <div class="col-12 q-px-md q-pb-none row">
          <q-input
            class="col-12"
            :label="$t('general.notes')"
            v-model="modifyableAthlete.notes"
            id="center_notes_field"
            name="center_notes_field"
            filled
            autogrow
          ></q-input>
        </div>
      </q-card-section>
    </q-card>

    <!-- Athlete body section -->
    <div class="from-group col-12 mt-2">
      <h3>
        {{ $t('general.requirements') }}:
        {{ $t('general.' + modifyableAthlete.gender) }} |
        {{ modifyableAthlete.requirements_tag }}
      </h3>
    </div>
    <div
      class="from-group col-12"
      v-for="category in categories"
      v-bind:key="category"
    >
      <h4 class="mt-2">{{ $t('general.' + category) }}</h4>
      <table class="requirements_table">
        <tr
          v-for="(discipline_array, discipline) in modifyableAthlete
            .needed_requirements[category]"
          v-bind:key="discipline"
        >
          <td style="width: 30%">
            {{ discipline }}
            <span
              class="help_symbol float-right"
              data-toggle="tooltip"
              data-placement="top"
              v-bind:title="discipline_array.description"
              v-if="discipline_array.description"
            ></span>
          </td>
          <td
            style="width: 16%"
            class="hide-overflow unselectable cursor-pointer"
            v-bind:class="{
              highlighted:
                modifyableAthlete.performances[category][discipline]
                  .bronze_highlighted,
            }"
            @click="toggleHighlight(category, discipline, 'bronze_highlighted')"
          >
            <span
              class="medal bronze float-left mr-1"
              data-toggle="tooltip"
              data-placement="top"
              v-bind:title="
                build_tooltip(
                  discipline_array.requirements.bronze.requirement_with_unit,
                  discipline_array.requirements.bronze.description
                )
              "
              @click.stop
            ></span>
            <span class="no-break">
              {{ discipline_array.requirements.bronze.requirement_with_unit }}
            </span>
          </td>
          <td
            style="width: 16%"
            class="hide-overflow unselectable cursor-pointer"
            v-bind:class="{
              highlighted:
                modifyableAthlete.performances[category][discipline]
                  .silver_highlighted,
            }"
            @click="toggleHighlight(category, discipline, 'silver_highlighted')"
          >
            <span
              class="medal silver float-left mr-1"
              data-toggle="tooltip"
              data-placement="top"
              v-bind:title="
                build_tooltip(
                  discipline_array.requirements.silver.requirement_with_unit,
                  discipline_array.requirements.silver.description
                )
              "
              @click.stop
            ></span>
            <span class="no-break">
              {{ discipline_array.requirements.silver.requirement_with_unit }}
            </span>
          </td>
          <td
            style="width: 16%"
            class="hide-overflow unselectable cursor-pointer"
            v-bind:class="{
              highlighted:
                modifyableAthlete.performances[category][discipline]
                  .gold_highlighted,
            }"
            @click="toggleHighlight(category, discipline, 'gold_highlighted')"
          >
            <span
              class="medal gold float-left mr-1"
              data-toggle="tooltip"
              data-placement="top"
              v-bind:title="
                build_tooltip(
                  discipline_array.requirements.gold.requirement_with_unit,
                  discipline_array.requirements.gold.description
                )
              "
              @click.stop
            ></span>
            <span class="no-break">
              {{ discipline_array.requirements.gold.requirement_with_unit }}
            </span>
          </td>
          <td style="width: 18%">
            <input
              type="text"
              class="mr-1 ml-1"
              style="width: 90%"
              v-model="
                modifyableAthlete.performances[category][discipline].performance
              "
              @change="
                updateAthletePerformance(
                  modifyableAthlete.id, // I hand this to the function to cache it, because I fear, we have race conditions, if the athlete changes before this gets executed, if a change athlete click triggers the change event.
                  category,
                  discipline
                )
              "
            />
          </td>
        </tr>
      </table>
    </div>
  </q-page>
  <q-page v-else-if="newAthlete != null" class="row q-pa-lg">
    <!-- NEW ATHLETE CREATION PART -->
    <q-card class="col-12" bordered>
      <q-card-section class="bg-primary text-white">
        <b>
          {{ $t('general.create_new') }}
        </b>
      </q-card-section>

      <q-separator></q-separator>

      <q-card-section class="row q-pa-md justify-center content-start">
        <div class="col-12 col-md-8">
          <q-input
            filled
            v-model="modifyableNewAthlete.name"
            :label="$t('general.name')"
            class="col-12 q-mb-md"
            id="center_name_field"
            name="center_name_field"
            lazy-rules
            :rules="[
              (val) => (val && val.length > 0) || $t('validation.notEmpty'),
            ]"
          ></q-input>
          <q-input
            filled
            v-model="modifyableNewAthlete.year"
            :label="$t('general.year')"
            class="col-12 q-mb-md"
            id="center_year_field"
            name="center_year_field"
            lazy-rules
            mask="####"
            :rules="[
              (val) => (val && val.length > 0) || $t('validation.notEmpty'),
            ]"
          ></q-input>
          <q-input
            filled
            class="col-12 q-mb-md"
            v-model="modifyableNewAthlete.birthday"
            :label="$t('general.birthday')"
            mask="date"
            :rules="[
              (val) =>
                !(val && val.length > 0) ||
                /^-?[\d]+\/[0-1]\d\/[0-3]\d$/.test(val) ||
                $t('validation.date'),
            ]"
            id="center_birthday_field"
            name="center_birthday_field"
          >
            <template v-slot:append>
              <q-icon name="event" class="cursor-pointer">
                <q-popup-proxy
                  ref="qDateProxy"
                  cover
                  transition-show="scale"
                  transition-hide="scale"
                >
                  <q-date v-model="modifyableNewAthlete.birthday">
                    <div class="row items-center justify-end">
                      <q-btn
                        v-close-popup
                        label="Close"
                        color="primary"
                        flat
                      ></q-btn>
                    </div>
                  </q-date>
                </q-popup-proxy>
              </q-icon>
            </template>
          </q-input>
          <q-select
            filled
            class="col-12 q-mb-md"
            v-model="modifyableNewAthlete.gender"
            emit-value
            map-options
            :label="$t('general.years')"
            :options="[
              { value: 'female', label: $t('general.female') },
              { value: 'male', label: $t('general.male') },
            ]"
            id="center_gender_field"
            name="center_gender_field"
          ></q-select>
          <q-btn
            color="primary"
            icon-right="send"
            :label="$t('general.save')"
            v-on:click="createAthlete"
          ></q-btn>
        </div>
      </q-card-section>
    </q-card>
  </q-page>
</template>

<script lang="ts">
import { mapGetters } from 'vuex';
import FavouriteStarButton from '../Includes/FavouriteStarButton.vue';
import { defineComponent } from 'vue';
import {
  Athlete,
  Category,
  Discipline,
  PerformanceArray,
} from 'src/store/athletes/state';

import { build_tooltip } from './../../string_helpers';

export default defineComponent({
  components: {
    FavouriteStarButton,
  },
  data() {
    return {
      canEdit: false,
      modifyableNewAthlete: {} as Athlete,
      modifyableAthlete: {} as Athlete,
      typingTimer: {} as { [key: string]: NodeJS.Timeout }, //use sub-elements to time different things, to prevent loss of data
    };
  },
  methods: {
    build_tooltip: build_tooltip,
    createAthlete: function () {
      void this.$store.dispatch(
        'athletesModule/createAthlete',
        this.modifyableNewAthlete
      );
    },
    toggleEdit() {
      if (this.canEdit) {
        // edit was avaliable until now, that means we need to save now
        void this.$store.dispatch(
          'athletesModule/updateAthlete',
          this.modifyableAthlete
        );
      }

      this.canEdit = !this.canEdit;
    },
    updateAthletePerformance: function (
      athlete_id: number,
      category: Category,
      discipline: Discipline
    ) {
      // I hand the athlete id to the function to cache it, because I fear, we have race conditions,
      // if the athlete changes before this gets executed, if a change athlete click triggers the change event.

      // create mockup athlete structure
      let mockup_athlete = { id: athlete_id } as Athlete;
      let performances = {} as {
        [key in Category]: { [key: Discipline]: PerformanceArray };
      };
      performances[category] = {};
      performances[category][discipline] = {} as PerformanceArray;

      // set the changed value
      performances[category][discipline].performance =
        this.modifyableAthlete.performances[category][discipline].performance;

      performances[category][discipline].bronze_highlighted =
        this.modifyableAthlete.performances[category][
          discipline
        ].bronze_highlighted;

      performances[category][discipline].silver_highlighted =
        this.modifyableAthlete.performances[category][
          discipline
        ].silver_highlighted;

      performances[category][discipline].gold_highlighted =
        this.modifyableAthlete.performances[category][
          discipline
        ].gold_highlighted;

      mockup_athlete.performances = performances;
      // dispatch the performance-update
      void this.$store.dispatch(
        'athletesModule/updateAthletePerformances',
        mockup_athlete
      );
    },
    toggleHighlight: function (
      category: Category,
      discipline: Discipline,
      type: 'bronze_highlighted' | 'silver_highlighted' | 'gold_highlighted'
    ) {
      if (this.modifyableAthlete.performances[category][discipline][type]) {
        this.modifyableAthlete.performances[category][discipline][type] = false;
      } else {
        this.modifyableAthlete.performances[category][discipline][type] = true;
      }
      this.$forceUpdate(); // don't know, why I need this, but hey it doesn't rerender otherwise.

      clearTimeout(this.typingTimer[discipline]);
      this.typingTimer[discipline] = setTimeout(() => {
        this.updateAthletePerformance(
          this.modifyableAthlete.id,
          category,
          discipline
        ); //store the update in the database
      }, 400);
    },
  },
  watch: {
    newAthlete: {
      deep: true,
      handler: function (newValue: Athlete) {
        // create a copy to perform local modifications on
        this.modifyableNewAthlete = JSON.parse(
          JSON.stringify(newValue)
        ) as Athlete;
      },
    },
    athlete: {
      deep: true,
      handler: function (newValue: Athlete) {
        // create a copy to perform local modifications on
        this.modifyableAthlete = JSON.parse(
          JSON.stringify(newValue)
        ) as Athlete;
      },
    },
    'modifyableAthlete.notes': function () {
      if (
        this.modifyableAthlete != null &&
        this.modifyableAthlete.notes != this.athlete.notes
      ) {
        clearTimeout(this.typingTimer['typing']);
        this.typingTimer['typing'] = setTimeout(() => {
          void this.$store.dispatch(
            'athletesModule/updateAthleteNotes',
            this.modifyableAthlete
          );
        }, 300);
      }
    },
  },
  computed: {
    categories: function () {
      return ['coordination', 'endurance', 'speed', 'strength'] as Category[];
    },
    ...(mapGetters('athletesModule', [
      'athlete',
      'newAthlete',
    ]) as MapToComputed<{
      athlete: Athlete;
      newAthlete: Athlete;
    }>),
  },
});
</script>
