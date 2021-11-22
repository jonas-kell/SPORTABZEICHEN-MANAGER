import { MutationTree } from 'vuex';
import { Athlete, AthletesStateInterface, YearResource } from './state';
import { SessionStorage } from 'quasar';
import { CURRENT_YEAR_STORAGE_KEY, ALL_YEARS_STORAGE_KEY } from './state';

const mutation: MutationTree<AthletesStateInterface> = {
  FETCH_FAVOURITES(state, favourites: Athlete[]) {
    return (state.favourites = favourites);
  },
  FETCH_ATHLETE(state, athlete: Athlete) {
    return (state.athlete = athlete);
  },
  SETUP_CREATE_ATHLETE(state, newAthlete: Athlete) {
    state.athlete = null;
    return (state.newAthlete = newAthlete);
  },
  DELETE_ATHLETE(state) {
    state.athlete = null;
  },
  UPDATE_ALL_YEARS_ARRAY(state, newYearsArray: YearResource) {
    SessionStorage.set(ALL_YEARS_STORAGE_KEY, newYearsArray.allYears);

    return (state.allYearsArray = newYearsArray.allYears);
  },
  UPDATE_CURRENT_YEAR(state, newCurrentYear: number) {
    SessionStorage.set(CURRENT_YEAR_STORAGE_KEY, newCurrentYear);

    return (state.currentYear = newCurrentYear);
  },
  FETCH_ATHLETE_SEARCH(state, athletes: Athlete[]) {
    return (state.searchedAthletes = athletes);
  },
  SET_SEARCH_STRING(state, search: string) {
    return (state.searchString = search);
  },
};

export default mutation;
