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
              : selectMode == 'finished'
              ? $t('general.selectFinishers')
              : selectMode == 'favourites'
              ? $t('general.favourites')
              : $t('general.selectNonFinishers')
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
          :label="$t('general.requestPDFOutput')"
          @click="requestOutputPDF"
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
          <th style="width: 17%">
            {{ $t('general.scores') }}
          </th>
          <th style="width: 15%" v-if="currentYear == -1">
            {{ $t('general.year') }}
          </th>
          <th style="width: 10%">
            {{ $t('general.proofOfSwimmingShort') }}
          </th>
          <th style="width: 10%">
            {{ $t('general.lastBadgeYear') }}
          </th>
          <th style="width: 10%">
            {{ $t('general.numberOfBadgesSoFar') }}
          </th>
          <th style="width: 10%">
            {{ $t('general.identNo') }}
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
          <td style="width: 17%">
            <medal-display-table :medalScores="athlete.medal_scores" />
          </td>
          <td style="width: 15%" v-if="currentYear == -1">
            {{ athlete.year }}
          </td>
          <td
            style="width: 10%"
            :style="{
              color: athlete.proofOfSwimmingOk ? 'green' : 'red',
            }"
          >
            <b>
              {{ athlete.proofOfSwimming }}
            </b>
          </td>
          <td style="width: 10%">
            {{ athlete.lastBadgeYear }}
          </td>
          <td style="width: 10%">
            {{ athlete.numberOfBadgesSoFar }}
          </td>
          <td style="width: 10%">
            {{ athlete.identNo }}
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
import { defineComponent } from 'vue';
import { Athlete, PerformanceArray } from '../../store/athletes/state';
import { SessionStorage } from 'quasar';
import DisciplineManager from './DisciplineManager.vue';

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
        | 'favourites',
      categories: ['coordination', 'endurance', 'speed', 'strength'],
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
      this.$q
        .dialog({
          component: DisciplineManager,
          componentProps: {
            athlete: athlete,
            category: category,
            discipline: discipline,
          },
        })
        .onOk(() => {
          console.log('OK');
        })
        .onDismiss(() => {
          console.log('Called on OK or Cancel');
        });
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
    handleSelectModeSwitch() {
      if (this.selectMode == 'favourites') {
        this.selectAllFavourites();
        this.selectMode = 'none';
      } else if (this.selectMode == 'none') {
        this.selectNone();
        this.selectMode = 'all';
      } else if (this.selectMode == 'all') {
        this.selectAll();
        this.selectMode = 'notFinished';
      } else if (this.selectMode == 'notFinished') {
        this.selectAllNonFinishers();
        this.selectMode = 'finished';
      } else {
        this.selectAllFinishers();
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
  },
});
</script>
