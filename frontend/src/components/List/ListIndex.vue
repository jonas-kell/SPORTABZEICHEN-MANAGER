<style scoped>
.stronger-table-border > tr > td,
.stronger-table-border > tr > th {
  border: 2px solid rgba(41, 41, 41, 0.51);
}
</style>

<template>
  <div class="row no-gutters ml-3 mr-3">
    <p class="col-12">
      <span class="btn btn-sm btn-edit float-right" @click="requestPDF">
        PDF erzeugen
      </span>
    </p>
    <table
      class="table table-sm table-bordered stronger-table-border"
      id="render-pdf"
      ref="render-pdf"
    >
      <tr>
        <th>{{ $t('general.name') }}</th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
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
      <tr v-for="n in 10" v-bind:key="-n">
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
  </div>
</template>

<script lang="ts">
import { mapGetters } from 'vuex';
import MedalDisplayTable from '../Includes/MedalDisplayTable.vue';
import { defineComponent } from 'vue';
import { Athlete } from '../../store/athletes/state';

export default defineComponent({
  components: { MedalDisplayTable },
  created() {
    //this.ALL_YEARS_STRING = "Alle"; //TODO make selector for favorites/non-favorites, clone year selector to this page
  },
  mounted() {
    void this.$store.dispatch('athletesModule/fetchFavourites');
  },
  computed: {
    ...(mapGetters('athletesModule', {
      favourites: 'favourites',
    }) as MapToComputed<{
      favourites: Athlete[];
    }>),
  },
  methods: {
    requestPDF() {
      void this.$store.dispatch(
        'athletesModule/requestPDF',
        document.getElementById((this.$refs['render-pdf'] as { id: string }).id)
          ?.outerHTML
      );
    },
  },
});
</script>
