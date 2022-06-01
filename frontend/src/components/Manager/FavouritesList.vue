<style scoped>
td {
  padding-left: 0.5rem;
}
</style>

<template>
  <q-card class="col-12" bordered>
    <q-card-section class="row q-pb-none">
      <div class="col">
        <q-btn
          color="primary"
          class="q-mr-sm q-mb-sm"
          icon-right="print"
          :label="$t('general.requestPDFList')"
          @click="requestTablePDF"
        ></q-btn>
      </div>
      <div class="col-2 q-pb-none">
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
      <table id="render-pdf" ref="render-pdf">
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
    </q-card-section>
  </q-card>
</template>

<script lang="ts">
import { mapGetters } from 'vuex';
import MedalDisplayTable from '../Includes/MedalDisplayTable.vue';
import { defineComponent } from 'vue';
import { Athlete } from '../../store/athletes/state';

export default defineComponent({
  components: { MedalDisplayTable },
  mounted() {
    this.fetchAthletes();
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
    };
  },
  watch: {
    currentYear: function () {
      this.fetchAthletes();
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
  },
});
</script>
