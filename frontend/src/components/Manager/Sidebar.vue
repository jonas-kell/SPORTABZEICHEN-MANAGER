<template>
  <div class="card card-fill">
    <div class="card-header">
      <div class="input-group">
        <input
          v-model="searchbar"
          class="form-control"
          :placeholder="$t('general.search')"
        />
        <div class="input-group-append">
          <span class="input-group-text search-icon unselectable"></span>
          <select v-model="yearsArray.current">
            <option
              v-for="year in yearsArray.allYears"
              v-bind:key="year"
              v-bind:value="year"
            >
              {{ year == -1 ? ALL_YEARS_STRING : year }}
            </option>
          </select>
        </div>
      </div>
    </div>

    <div class="card-body row no-gutters justify-content-center">
      <span
        class="btn btn-edit btn-sm mb-2 col-lg-8 col-md-12 col-8"
        @click="setupCreateAthlete"
      >
        ++ {{ $t('general.create_new') }} ++
      </span>
      <sidebar-list
        class="col-12 vh-search"
        v-bind="{
          currentYear: yearsArray.current,
          list: searchedAthletes,
        }"
      />
    </div>
    <hr class="m-0" />
    <div class="card-body row no-gutters">
      <h5>{{ $t('general.favourites') }}</h5>

      <sidebar-list
        class="col-12 vh-favourites"
        v-bind="{
          currentYear: yearsArray.current,
          list: favourites,
        }"
      />
    </div>
  </div>
</template>

<script lang="ts">
import { mapActions, mapGetters } from 'vuex';
import SidebarList from '../Includes/SidebarList.vue';
import { defineComponent } from 'vue';
import { Athlete, Year } from '../../store/athletes/state';

export default defineComponent({
  components: { SidebarList },
  mounted() {
    void this.$store.dispatch('athletesModule/fetchFavourites');
    void this.$store.dispatch('athletesModule/getYear');
  },
  data() {
    return {
      searchbar: '',
      ALL_YEARS_STRING: 'Alle',
      typingTimer: setTimeout(() => ({}), 0),
    };
  },
  methods: {
    ...(mapActions('athletesModule', {
      mappedSetupCreateAthlete: 'setupCreateAthlete',
    }) as unknown as {
      mappedSetupCreateAthlete: (athlete: Athlete) => string;
    }),
    setupCreateAthlete: function () {
      this.mappedSetupCreateAthlete({
        name: this.searchbar,
        year: this.yearsArray.current,
        birthday: null,
        gender: 'male',
      } as Athlete);
    },
  },
  watch: {
    'yearsArray.current': function (newValue) {
      //TODO also calls on initial laod. sets unnecessary. maybe resolve.
      if (newValue == this.ALL_YEARS_STRING) {
        void this.$store.dispatch(
          'athletesModule/setYear',
          -1 as unknown as undefined
        );
      } else {
        void this.$store.dispatch('athletesModule/setYear', newValue);
      }
    },
    searchbar: function (newValue) {
      let store = this.$store;
      clearTimeout(this.typingTimer);
      this.typingTimer = setTimeout(function () {
        void store.dispatch('athletesModule/fetchSearch', newValue);
      }, 250);
    },
  },
  computed: {
    ...(mapGetters('athletesModule', [
      'favourites',
      'yearsArray',
      'searchedAthletes',
    ]) as MapToComputed<{
      favourites: Athlete[];
      yearsArray: Year;
      searchedAthletes: Athlete[];
    }>),
  },
});
</script>
