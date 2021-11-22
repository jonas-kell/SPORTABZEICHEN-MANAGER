<template>
  <template v-if="isLoggedIn">
    <q-input
      class="q-pa-md"
      filled
      v-model="searchbar"
      :label="$t('general.search')"
      id="searchbar"
      name="searchbar"
      clearable
    >
      <template v-slot:append>
        <span class="input-group-text search-icon unselectable"></span>

        <q-icon name="search"></q-icon>
      </template>
    </q-input>
    <select v-model="currentYear">
      <option
        v-for="year in allYearsArray"
        v-bind:key="year"
        v-bind:value="year"
      >
        {{ year == -1 ? $t('general.all') : year }}
      </option>
    </select>

    <span
      class="btn btn-edit btn-sm mb-2 col-lg-8 col-md-12 col-8"
      @click="setupCreateAthlete"
    >
      ++ {{ $t('general.create_new') }} ++
    </span>
    <sidebar-list
      class="col-12 vh-search"
      v-bind="{
        currentYear: currentYear,
        list: searchedAthletes,
      }"
    />

    <q-item-label class="q-py-sm" header>
      {{ $t('general.favourites') }}
    </q-item-label>
    <sidebar-list
      class="col-12 vh-favourites"
      v-bind="{
        currentYear: currentYear,
        list: favourites,
      }"
    />
  </template>
</template>

<script lang="ts">
import { mapActions, mapGetters, mapState } from 'vuex';
import SidebarList from '../Includes/SidebarList.vue';
import { defineComponent } from 'vue';
import { Athlete } from '../../store/athletes/state';

export default defineComponent({
  components: { SidebarList },
  mounted() {
    void this.$store.dispatch('athletesModule/fetchFavourites');
    void this.$store.dispatch('athletesModule/getYear');
  },
  data() {
    return {
      searchbar: '',
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
        year: this.currentYear,
        birthday: null,
        gender: 'male',
      } as Athlete);
    },
  },
  watch: {
    searchbar: function (newValue) {
      let store = this.$store;
      clearTimeout(this.typingTimer);
      this.typingTimer = setTimeout(function () {
        void store.dispatch('athletesModule/fetchSearch', newValue);
      }, 250);
    },
  },
  computed: {
    currentYear: {
      get(): number {
        return (
          this.$store.getters as { 'athletesModule/currentYear': number }
        )['athletesModule/currentYear'];
      },
      set(newValue: number) {
        void this.$store.dispatch('athletesModule/setYear', newValue);
      },
    },
    ...(mapGetters('athletesModule', [
      'favourites',
      'searchedAthletes',
      'allYearsArray',
    ]) as MapToComputed<{
      favourites: Athlete[];
      searchedAthletes: Athlete[];
      allYearsArray: number[];
    }>),
    ...(mapState('authModule', ['isLoggedIn']) as MapToComputed<{
      isLoggedIn: boolean;
    }>),
  },
});
</script>
