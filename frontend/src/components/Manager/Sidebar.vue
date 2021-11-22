<style>
.vh-search {
  max-height: 30vh;
}

.vh-favourites {
  max-height: 70vh;
}
</style>

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

    <div class="row q-mx-md">
      <q-select
        class="col-4 q-pr-sm"
        outlined
        v-model="currentYear"
        emit-value
        map-options
        :label="$t('general.years')"
        :dense="true"
        :options-dense="true"
        :options="dropdownFormattedYears"
      ></q-select>
      <q-btn
        class="col-8 q-pl-sm"
        color="primary"
        icon-right="add"
        :label="$t('general.create_new')"
        @click="setupCreateAthlete"
        :disable="searchbar == '' || searchbar == null ? true : null"
      ></q-btn>
    </div>

    <q-item-label class="q-py-sm" header>
      {{ $t('general.search') }}
    </q-item-label>

    <div class="q-px-md row">
      <sidebar-list
        class="q-mx-md col-12 vh-search"
        v-bind="{
          currentYear: currentYear,
          list: searchedAthletes,
        }"
      />
    </div>

    <q-item-label class="q-py-sm" header>
      {{ $t('general.favourites') }}
    </q-item-label>

    <div class="q-px-md row">
      <sidebar-list
        class="col-12 vh-favourites"
        v-bind="{
          currentYear: currentYear,
          list: favourites,
        }"
      />
    </div>
  </template>
</template>

<script lang="ts">
import { mapActions, mapGetters, mapState } from 'vuex';
import SidebarList from '../Includes/SidebarList.vue';
import { defineComponent } from 'vue';
import { Athlete } from '../../store/athletes/state';

interface DropdownOption {
  value: number;
  label: string;
}

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
        year:
          this.currentYear != -1 ? this.currentYear : new Date().getFullYear(),
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
    dropdownFormattedYears: function () {
      let formatted = [] as DropdownOption[];

      this.allYearsArray.forEach((year) => {
        if (year == -1) {
          formatted.push({ label: this.$t('general.all'), value: -1 });
        } else {
          formatted.push({ label: String(year), value: year });
        }
      });

      return formatted;
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
