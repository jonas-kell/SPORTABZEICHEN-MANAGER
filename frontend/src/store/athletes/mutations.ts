import { MutationTree } from 'vuex';
import { Athlete, AthletesStateInterface } from './state';

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
  UPDATE_YEARS_ARRAY(state, yearsArray: number[]) {
    return (state.yearsArray = yearsArray);
  },
  FETCH_ATHLETE_SEARCH(state, athletes: Athlete[]) {
    return (state.searchedAthletes = athletes);
  },
};

export default mutation;
