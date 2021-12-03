<style scoped>
td {
  padding-left: 0.5rem;
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
            { label: $t('general.favourites'), value: 'favourites' },
            { label: $t('general.all'), value: 'all' },
          ]"
        ></q-btn-toggle>
      </div>
      <div class="col" v-if="mode == 'all'">
        <q-btn
          icon="check_box"
          color="primary"
          :label="
            selectMode == 'none'
              ? $t('general.selectNone')
              : $t('general.selectFinishers')
          "
          @click="handleSelectModeSwitch"
        ></q-btn>
      </div>
      <div class="col">
        <q-btn
          color="primary"
          class="q-mr-sm"
          icon-right="print"
          :label="$t('general.requestPDFList')"
          @click="requestTablePDF"
        ></q-btn>
        <q-btn
          v-if="mode == 'all'"
          color="primary"
          icon-right="print"
          :label="$t('general.requestPDFOutput')"
          @click="requestOutputPDF"
        ></q-btn>
      </div>
      <div class="col-2 q-pb-none" v-if="mode == 'favourites'">
        <q-input
          filled
          :dense="true"
          :label="$t('general.new')"
          class="q-pb-none"
          v-model.number="numberOfExtraCols"
          type="number"
          :rules="[(val) => val && val > 0]"
        ></q-input>
      </div>
    </q-card-section>
    <q-card-section class="col-12">
      <table id="render-pdf" ref="render-pdf" v-if="mode == 'favourites'">
        <tr>
          <th>{{ $t('general.name') }}</th>
          <th style="width: 9%"></th>
          <th style="width: 17%"></th>
          <th style="width: 10%"></th>
          <th style="width: 10%"></th>
          <th style="width: 10%"></th>
          <th style="width: 10%"></th>
          <th style="width: 10%"></th>
        </tr>
        <tr v-for="athlete in favourites" v-bind:key="athlete.id">
          <td>
            <strong>
              {{ athlete.name }}
            </strong>
          </td>
          <td
            style="width: 9%"
            v-bind:style="{
              color: athlete.gender == 'male' ? 'blue' : 'red',
            }"
          >
            {{ $t('general.gender_short_' + athlete.gender) }} |
            {{ athlete.requirements_tag }}
          </td>
          <td style="width: 17%">
            <medal-display-table :medalScores="athlete.medal_scores" />
          </td>
          <td style="width: 10%"></td>
          <td style="width: 10%"></td>
          <td style="width: 10%"></td>
          <td style="width: 10%"></td>
          <td style="width: 10%"></td>
        </tr>
        <tr
          v-for="n in Math.min(Math.max(numberOfExtraCols, 0), 100)"
          v-bind:key="-n"
        >
          <!-- binding normal numbes causes duplicate-key issues, when the ids used here are the same as real athletes ones by chance -->
          <td></td>
          <td style="width: 9%"></td>
          <td style="width: 17%">
            <strong>
              {{ $t('general.new') }}
            </strong>
          </td>
          <td style="width: 10%"></td>
          <td style="width: 10%"></td>
          <td style="width: 10%"></td>
          <td style="width: 10%"></td>
          <td style="width: 10%"></td>
        </tr>
      </table>
      <table id="render-pdf" ref="render-pdf" v-else>
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
            {{ $t('general.gender_short_' + athlete.gender) }} |
            {{ athlete.requirements_tag }}
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
              color:
                (athlete.year - athlete.proofOfSwimming ?? 0) < 5
                  ? 'green'
                  : 'red',
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
    </q-card-section>
  </q-card>
</template>

<script lang="ts">
import { mapGetters } from 'vuex';
import MedalDisplayTable from '../Includes/MedalDisplayTable.vue';
import { defineComponent } from 'vue';
import { Athlete } from '../../store/athletes/state';
import { SessionStorage } from 'quasar';

const MODE_STORAGE_KEY = 'TABLEMODEINSESSIONSTORAGE';

export default defineComponent({
  components: { MedalDisplayTable },
  mounted() {
    this.fetchAthletes();

    this.mode = SessionStorage.getItem(MODE_STORAGE_KEY) as
      | 'favourites'
      | 'all';
    if (!this.mode) {
      this.mode = 'all';
    }
  },
  computed: {
    ...(mapGetters('athletesModule', {
      favourites: 'favourites',
      allRelevant: 'allRelevantAthletes',
      currentYear: 'currentYear',
    }) as MapToComputed<{
      favourites: Athlete[];
      allRelevant: Athlete[];
      currentYear: number;
    }>),
  },
  data() {
    return {
      numberOfExtraCols: 10,
      mode: 'favourites' as 'favourites' | 'all',
      selected: [] as boolean[],
      selectMode: 'none' as 'finished' | 'none',
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
    revealAthlete: function (id: number) {
      void this.$store.dispatch('athletesModule/fetchAthlete', id);

      void this.$router.push('/');
    },
    fetchAthletes: function () {
      void this.$store.dispatch('athletesModule/fetchRelevantAthletes');
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
      if (this.selectMode == 'finished') {
        this.selectAllFinishers();
        this.selectMode = 'none';
      } else if (this.selectMode == 'none') {
        this.selectNone();
        this.selectMode = 'finished';
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
  },
});
</script>
