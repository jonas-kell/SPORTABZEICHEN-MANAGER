import { GetterTree } from 'vuex';
import { StateInterface } from '../index';
import { AthletesStateInterface } from './state';

const getters: GetterTree<AthletesStateInterface, StateInterface> = {
  athlete: (state) => {
    return state.athlete;
  },
  newAthlete: (state) => {
    return state.newAthlete;
  },
  allYearsArray: (state) => {
    return state.allYearsArray;
  },
  currentYear: (state) => {
    return state.currentYear;
  },
  searchedAthletes: (state) => {
    return state.searchedAthletes;
  },
  favourites: (state) => {
    return state.favourites;
  },
  allRelevantAthletes: (state) => {
    return state.allRelevantAthletes;
  },
  searchString: (state) => {
    return state.searchString;
  },
};

export default getters;
