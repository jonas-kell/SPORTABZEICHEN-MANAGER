<style scoped>
td {
  padding-left: 0.5rem;
}
.verticalTableHeader p {
  text-align: center;
  padding-left: 30%;
}
.verticalTableHeader p {
  writing-mode: vertical-lr;
  margin-top: 0.3em;
}
td.highlighted {
  background-color: #33af229a;
}
</style>

<template>
  <q-card class="col-12" bordered>
    <q-card-section class="row q-pb-none">
      <div class="col">
        <q-btn-toggle
          v-model="mode"
          toggle-color="primary"
          :options="[
            { label: $t('general.meta'), value: 'meta' },
            { label: $t('general.performances'), value: 'performances' },
          ]"
        ></q-btn-toggle>
      </div>
      <div class="col">
        <q-btn
          icon="check_box"
          color="primary"
          :label="
            selectMode == 'none'
              ? $t('general.selectNone')
              : selectMode == 'all'
              ? $t('general.selectAll')
              : selectMode == 'needsSubmit'
              ? $t('general.selectNeedsSubmit')
              : selectMode == 'finished'
              ? $t('general.selectFinishers')
              : selectMode == 'favourites'
              ? $t('general.favourites')
              : selectMode == 'notFinished'
              ? $t('general.selectNonFinishers')
              : selectMode == 'official'
              ? $t('general.selectOfficial')
              : $t('general.selectNonOfficial')
          "
          @click="handleSelectModeSwitch"
        ></q-btn>
      </div>
      <div class="col">
        <q-btn
          color="primary"
          class="q-mr-sm q-mb-sm"
          icon-right="print"
          :label="$t('general.requestPDFList')"
          @click="requestTablePDF"
        ></q-btn>
        <q-btn
          class="q-mr-sm q-mb-sm"
          color="primary"
          icon-right="print"
          v-if="mode == 'performances'"
          :label="$t('general.requestPDFOutput')"
          @click="requestOutputPDF"
        ></q-btn>
        <q-btn
          class="q-mr-sm q-mb-sm"
          color="primary"
          icon-right="print"
          v-else
          :label="$t('general.requestPDFShow')"
          @click="requestShowPDF"
        ></q-btn>
        <q-btn
          class="q-mr-sm q-mb-sm"
          color="primary"
          icon-right="download"
          v-if="mode == 'meta'"
          :label="$t('general.export')"
          @click="requestExport"
        ></q-btn>
      </div>
    </q-card-section>
    <q-card-section class="col-12">
      <table id="render-pdf" ref="render-pdf" v-if="mode == 'meta'">
        <tr>
          <th class="printout-display-none" style="width: 4%"></th>
          <th>
            {{
              currentYear == -1
                ? ''
                : $t('general.year') + ': ' + String(currentYear) + ' | '
            }}{{ $t('general.name') }}
          </th>
          <th style="width: 9%"></th>
          <th style="width: 20%">
            {{ $t('general.scores') }}
          </th>
          <th style="width: 10%" v-if="currentYear == -1">
            {{ $t('general.year') }}
          </th>
          <th style="width: 8%">
            {{ $t('general.proofOfSwimmingShort') }}
          </th>
          <th style="width: 8%">
            {{ $t('general.lastBadgeYear') }}
          </th>
          <th style="width: 8%">
            {{ $t('general.numberOfBadgesSoFar') }}
          </th>
          <th style="width: 8%" v-if="!hideCols">
            {{ $t('general.lastYearIdentNo') }}
          </th>
          <th style="width: 8%" v-if="!hideCols">
            {{ $t('general.newIdentNo') }}
          </th>
          <th style="width: 8%">
            {{ $t('general.totalScore') }}
          </th>
        </tr>
        <tr v-for="athlete in allRelevant" v-bind:key="athlete.id">
          <td
            class="printout-display-none"
            style="width: 4%"
            v-bind:style="{
              background: selected[athlete.id] ? 'green' : 'red',
            }"
          >
            <q-checkbox v-model="selected[athlete.id]" size="xs"></q-checkbox>
          </td>
          <td>
            <strong
              class="cursor-pointer"
              @click="revealAthlete(athlete.id)"
              :style="athlete.hasFinished ? 'color: green;' : 'color: red;'"
            >
              {{ athlete.name }}
            </strong>
          </td>
          <td
            style="width: 9%"
            v-bind:style="{
              color: athlete.gender == 'male' ? 'blue' : 'red',
            }"
          >
            {{ getGenderAndAgeString(athlete) }}
          </td>
          <td style="width: 20%">
            <medal-display-table :medalScores="athlete.medal_scores" />
          </td>
          <td style="width: 10%" v-if="currentYear == -1">
            {{ athlete.year }}
          </td>
          <td
            style="width: 8%"
            class="cursor-pointer"
            :style="{
              color: athlete.proofOfSwimmingOk ? 'green' : 'red',
            }"
            @click="editDialogSwimmingYear(athlete)"
          >
            <b>
              {{ athlete.proofOfSwimming }}
            </b>
          </td>
          <td style="width: 8%">
            {{ athlete.lastBadgeYear }}
          </td>
          <td style="width: 8%">
            {{ athlete.numberOfBadgesSoFar }}
          </td>
          <td style="width: 8%" v-if="!hideCols">
            {{ athlete.lastYearIdentNo }}
          </td>
          <td style="width: 8%" v-if="!hideCols">
            {{ athlete.newIdentNo }}
          </td>
          <td style="width: 8%">
            {{ getScoreString(athlete) }}
          </td>
        </tr>
      </table>

      <div
        id="render-pdf"
        ref="render-pdf"
        v-if="mode == 'performances' && currentYear != -1"
      >
        <div
          class="wrapper-page"
          v-for="(athletes, ageGroup) in relevantAgeGroupsWithAthletes"
          :key="ageGroup"
          v-bind:class="{
            'printout-display-none': !athletes.some(
              (athlete) => selected[athlete.id]
            ),
          }"
        >
          <table style="margin-bottom: 1cm" class="smoll-in-printout">
            <tr>
              <th>{{ ageGroup }}</th>
              <th class="printout-display-none"></th>
              <th
                v-for="category in categories"
                :key="category"
                :colspan="Object.keys(athletes[0].needed_requirements[
                    category as 'coordination'| 'endurance' | 'speed'| 'strength'
                  ]).length"
              >
                {{ $t('general.' + category) }}
              </th>
            </tr>
            <tr>
              <th></th>
              <th class="printout-display-none"></th>
              <template v-for="category in categories" :key="category">
                <th
                  v-for="(requirements, discipline) in athletes[0].needed_requirements[
                    category as 'coordination'| 'endurance' | 'speed'| 'strength'
                  ]"
                  :key="discipline"
                  class="verticalTableHeader"
                >
                  <p>{{ shortenDescription(discipline as string) }}</p>
                </th>
              </template>
            </tr>
            <tr v-for="rank in ['gold', 'silver', 'bronze']" :key="rank">
              <th class="printout-display-none"></th>
              <th style="font-size: 70%">
                {{ $t('general.' + rank) }}
              </th>
              <template v-for="category in categories" :key="category">
                <td
                  v-for="(requirements, discipline) in athletes[0].needed_requirements[
                    category as 'coordination'| 'endurance' | 'speed'| 'strength'
                  ]"
                  :key="discipline"
                  class="verticalTableHeader"
                >
                  {{ requirements.requirements[rank as 'gold'| 'silver'| 'bronze'].requirement_with_unit }}
                </td>
              </template>
            </tr>
            <tr
              v-for="athlete in athletes"
              :key="athlete.id"
              v-bind:class="{ 'printout-display-none': !selected[athlete.id] }"
            >
              <td
                class="printout-display-none"
                style="width: 4%"
                v-bind:style="{
                  background: selected[athlete.id] ? 'green' : 'red',
                }"
              >
                <q-checkbox
                  v-model="selected[athlete.id]"
                  size="xs"
                ></q-checkbox>
              </td>
              <td>
                <strong
                  class="cursor-pointer"
                  @click="revealAthlete(athlete.id)"
                  :style="athlete.hasFinished ? 'color: green;' : 'color: red;'"
                >
                  {{ athlete.name }}
                </strong>
              </td>
              <template v-for="category in categories" :key="category">
                <td
                  v-for="(_, discipline) in athletes[0].needed_requirements[
                    category as 'coordination'| 'endurance' | 'speed'| 'strength'
                  ]"
                  :key="discipline"
                  style="height: 1cm"
                  v-bind:class="{
                    highlighted:
                      athlete.performances[category as 'coordination'| 'endurance' | 'speed'| 'strength'][discipline].bronze_highlighted ||
                      athlete.performances[category as 'coordination'| 'endurance' | 'speed'| 'strength'][discipline].silver_highlighted ||
                      athlete.performances[category as 'coordination'| 'endurance' | 'speed'| 'strength'][discipline].gold_highlighted,
                    'cursor-pointer': athlete.canStillBeEdited
                  }"
                  @click="editDialog(athlete, category as 'coordination'| 'endurance' | 'speed'| 'strength', discipline as string)"
                >
                  {{ formatPerformance(athlete.performances[category as 'coordination'| 'endurance' | 'speed'| 'strength'][discipline]) }}
                </td>
              </template>
            </tr>
          </table>
        </div>
      </div>

      <q-page v-if="mode == 'performances' && currentYear == -1">
        <h5>
          <p class="text-center">
            {{ $t('general.table_notice') }}
          </p>
        </h5>
      </q-page>
    </q-card-section>
  </q-card>
</template>

<script lang="ts">
import { mapGetters } from 'vuex';
import MedalDisplayTable from '../Includes/MedalDisplayTable.vue';
import { defineComponent, nextTick } from 'vue';
import { Athlete, PerformanceArray } from '../../store/athletes/state';
import { SessionStorage } from 'quasar';
import DisciplineManager from './DisciplineManager.vue';
import SwimmingManager from './SwimmingManager.vue';

const MODE_STORAGE_KEY = 'TABLEMODEINSESSIONSTORAGE';

export default defineComponent({
  components: { MedalDisplayTable },
  mounted() {
    this.fetchAthletes();

    this.mode = SessionStorage.getItem(MODE_STORAGE_KEY) as
      | 'performances'
      | 'meta';
    if (!this.mode) {
      this.mode = 'performances';
    }
  },
  computed: {
    ...(mapGetters('athletesModule', {
      allRelevant: 'allRelevantAthletes',
      currentYear: 'currentYear',
    }) as MapToComputed<{
      allRelevant: Athlete[];
      currentYear: number;
    }>),
    relevantAgeGroupsWithAthletes: function () {
      let groups = {} as { [key: string]: Athlete[] };

      this.allRelevant.forEach((athlete) => {
        // without this check it randomly fails in render
        if (athlete.needed_requirements != undefined) {
          let key = this.getGenderAndAgeString(athlete);

          if (groups[key] == undefined) {
            groups[key] = [];
          }

          groups[key].push(athlete);
        }
      });

      return groups;
    },
  },
  data() {
    return {
      mode: 'performances' as 'performances' | 'meta',
      selected: [] as boolean[],
      selectMode: 'none' as
        | 'finished'
        | 'notFinished'
        | 'none'
        | 'all'
        | 'favourites'
        | 'official'
        | 'notOfficial'
        | 'needsSubmit',
      categories: ['coordination', 'endurance', 'speed', 'strength'],
      hideCols: false,
    };
  },
  watch: {
    currentYear: function () {
      this.fetchAthletes();
    },
    mode: function () {
      SessionStorage.set(MODE_STORAGE_KEY, this.mode);
    },
    allRelevant: function () {
      this.selectAllFinishers();
    },
  },
  methods: {
    editDialog(
      athlete: Athlete,
      category: 'coordination' | 'endurance' | 'speed' | 'strength',
      discipline: string
    ) {
      if (athlete.canStillBeEdited) {
        this.$q.dialog({
          component: DisciplineManager,
          componentProps: {
            athlete: athlete,
            category: category,
            discipline: discipline,
          },
        });
      } else {
        this.$q.dialog({
          title: '',
          message: this.$t('general.athleteCanNoLongerBeEdited'),
        });
      }
    },
    editDialogSwimmingYear(athlete: Athlete) {
      if (athlete.canStillBeEdited) {
        this.$q.dialog({
          component: SwimmingManager,
          componentProps: {
            athlete: athlete,
          },
        });
      } else {
        this.$q.dialog({
          title: '',
          message: this.$t('general.athleteCanNoLongerBeEdited'),
        });
      }
    },
    formatPerformance: function (performance: PerformanceArray) {
      let highest = '';

      if (performance.bronze_highlighted) {
        highest = ' (1)';
      }
      if (performance.silver_highlighted) {
        highest = ' (2)';
      }
      if (performance.gold_highlighted) {
        highest = ' (3)';
      }

      return performance.performance + highest;
    },
    shortenDescription: function (name: string) {
      if (name.length < 33) {
        return name;
      } else {
        return name.substring(0, name.indexOf(' '));
      }
    },
    getGenderAndAgeString: function (athlete: Athlete) {
      return (
        this.$t('general.gender_short_' + athlete.gender) +
        ' | ' +
        athlete.requirements_tag
      );
    },
    getScoreString: function (athlete: Athlete) {
      let coordination = parseInt(
        athlete.medal_scores.coordination.points as unknown as string
      );
      let speed = parseInt(
        athlete.medal_scores.speed.points as unknown as string
      );
      let strength = parseInt(
        athlete.medal_scores.strength.points as unknown as string
      );
      let endurance = parseInt(
        athlete.medal_scores.endurance.points as unknown as string
      );

      let sum = coordination + speed + strength + endurance;
      let minOne =
        coordination > 0 && speed > 0 && strength > 0 && endurance > 0;

      if (!minOne || !athlete.proofOfSwimmingOk) {
        return '-- (' + sum + ')';
      } else {
        if (sum < 8) {
          return 'Bronze (' + sum + ')';
        } else if (sum < 11) {
          return 'Silber (' + sum + ')';
        } else {
          return 'Gold (' + sum + ')';
        }
      }
    },
    revealAthlete: function (id: number) {
      void this.$store.dispatch('athletesModule/fetchAthlete', id);

      void this.$router.push('/');
    },
    fetchAthletes: function () {
      void this.$store.dispatch('athletesModule/fetchRelevantAthletes', true);
    },
    requestTablePDF() {
      void this.$store.dispatch(
        'athletesModule/requestTablePDF',
        document.getElementById((this.$refs['render-pdf'] as { id: string }).id)
          ?.outerHTML
      );
    },
    requestOutputPDF() {
      let arrayOfIds = [] as number[];

      this.allRelevant.forEach((athlete) => {
        if (this.selected[athlete.id]) {
          arrayOfIds.push(athlete.id);
        }
      });

      void this.$store.dispatch('athletesModule/requestOutputPDF', arrayOfIds);
    },
    requestExport() {
      let arrayOfIds = [] as number[];

      this.allRelevant.forEach((athlete) => {
        if (this.selected[athlete.id]) {
          arrayOfIds.push(athlete.id);
        }
      });

      void this.$store.dispatch('athletesModule/requestJSONExport', arrayOfIds);
    },
    requestShowPDF() {
      this.hideCols = true;

      nextTick(async () => {
        await this.$store.dispatch(
          'athletesModule/requestTablePDF',
          document.getElementById(
            (this.$refs['render-pdf'] as { id: string }).id
          )?.outerHTML
        );

        this.hideCols = false;
      });
    },
    handleSelectModeSwitch() {
      if (this.selectMode == 'favourites') {
        this.selectAllFavourites();
        this.selectMode = 'none';
      } else if (this.selectMode == 'none') {
        this.selectNone();
        this.selectMode = 'needsSubmit';
      } else if (this.selectMode == 'needsSubmit') {
        this.selectNeedsSubmit();
        this.selectMode = 'all';
      } else if (this.selectMode == 'all') {
        this.selectAll();
        this.selectMode = 'notFinished';
      } else if (this.selectMode == 'notFinished') {
        this.selectAllNonFinishers();
        this.selectMode = 'finished';
      } else if (this.selectMode == 'finished') {
        this.selectAllFinishers();
        this.selectMode = 'official';
      } else if (this.selectMode == 'official') {
        this.selectAllOfficial();
        this.selectMode = 'notOfficial';
      } else {
        this.selectAllNotOfficial();
        this.selectMode = 'favourites';
      }
    },
    selectAllFinishers() {
      this.selected = [];
      this.allRelevant.forEach((athlete) => {
        this.selected[athlete.id] = athlete.hasFinished;
      });
    },
    selectNone() {
      this.selected = [];
      this.allRelevant.forEach((athlete) => {
        this.selected[athlete.id] = false;
      });
    },
    selectAll() {
      this.selected = [];
      this.allRelevant.forEach((athlete) => {
        this.selected[athlete.id] = true;
      });
    },
    selectAllNonFinishers() {
      this.selected = [];
      this.allRelevant.forEach((athlete) => {
        this.selected[athlete.id] = !athlete.hasFinished;
      });
    },
    selectAllFavourites() {
      this.selected = [];
      this.allRelevant.forEach((athlete) => {
        this.selected[athlete.id] = athlete.favourite;
      });
    },
    selectNeedsSubmit() {
      this.selected = [];
      this.allRelevant.forEach((athlete) => {
        this.selected[athlete.id] =
          athlete.hasFinished && athlete.canStillBeEdited; // finished, but not yet submitted
      });
    },
    selectAllOfficial() {
      this.selected = [];
      this.allRelevant.forEach((athlete) => {
        this.selected[athlete.id] = !athlete.canStillBeEdited;
      });
    },
    selectAllNotOfficial() {
      this.selected = [];
      this.allRelevant.forEach((athlete) => {
        this.selected[athlete.id] = athlete.canStillBeEdited;
      });
    },
  },
});
</script>
