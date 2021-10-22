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
  yearsArray: (state) => {
    return state.yearsArray;
  },
  searchedAthletes: (state) => {
    return state.searchedAthletes;
  },
  favourites: (state) => {
    return state.favourites;
  },
  searchString: (state) => {
    return state.searchString;
  },
};

export default getters;
