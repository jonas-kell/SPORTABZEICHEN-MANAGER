<template>
  <q-card class="col-12" bordered>
    <q-card-section class="col-12 q-pb-none">
      <q-btn
        color="primary"
        icon-right="print"
        :label="$t('general.requestPDF')"
        @click="requestPDF"
      ></q-btn>
    </q-card-section>
    <q-card-section class="col-12">
      <table id="render-pdf" ref="render-pdf">
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
