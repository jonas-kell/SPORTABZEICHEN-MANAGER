import { Module } from 'vuex';
import { StateInterface } from '../index';
import state, { AthletesStateInterface } from './state';
import actions from './actions';
import getters from './getters';
import mutations from './mutations';

const athletesModule: Module<AthletesStateInterface, StateInterface> = {
  namespaced: true,
  actions,
  getters,
  mutations,
  state,
};

export default athletesModule;
