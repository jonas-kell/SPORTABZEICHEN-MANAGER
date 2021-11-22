import axios, { AxiosResponse } from 'axios';
import { ActionTree } from 'vuex';
import { StateInterface } from '../index';
import { AthletesStateInterface, Athlete, YearResource } from './state';
import { exportFile } from 'quasar';
import { Notify } from 'quasar';
import { i18n } from './../../boot/i18n';

const actions: ActionTree<AthletesStateInterface, StateInterface> = {
  fetchFavourites({ commit }) {
    axios
      .get('api/favourites')
      .then((res: AxiosResponse<{ athletes: Athlete[] }>) => {
        commit('FETCH_FAVOURITES', res.data.athletes);

        // Notify the user of the fetch
        Notify.create({
          type: 'reloaded',
          message: i18n.global.t('general.reloadedFavourites'),
        });
      })
      .catch((err) => {
        notifyOfUnknownError(err);
      });
  },
  addFavourite({ dispatch, commit, state }, athlete: Athlete) {
    axios
      .put(`api/favourites/add/${encodeURIComponent(athlete.id)}`)
      .then((res: AxiosResponse<{ athletes: Athlete[] }>) => {
        commit('FETCH_FAVOURITES', res.data.athletes);

        //update search and center athlete asynchronously
        void dispatch('fetchSearch');
        if (state.athlete !== null) {
          void dispatch('fetchAthlete', state.athlete.id);
        }
      })
      .catch((err) => {
        notifyOfUnknownError(err);
      });
  },
  dropFavourite({ dispatch, commit, state }, athlete: Athlete) {
    axios
      .put(`api/favourites/drop/${encodeURIComponent(athlete.id)}`)
      .then((res: AxiosResponse<{ athletes: Athlete[] }>) => {
        commit('FETCH_FAVOURITES', res.data.athletes);

        //update search and center athlete asynchronously
        void dispatch('fetchSearch');
        if (state.athlete !== null) {
          void dispatch('fetchAthlete', state.athlete.id);
        }
      })
      .catch((err) => {
        notifyOfUnknownError(err);
      });
  },
  fetchAthlete({ commit }, athlete_id: number) {
    axios
      .get(`/api/athlete/${encodeURIComponent(athlete_id)}`)
      .then((res: AxiosResponse<{ data: Athlete }>) => {
        commit('FETCH_ATHLETE', res.data.data);
      })
      .catch((err) => {
        notifyOfUnknownError(err);
      });
  },
  setupCreateAthlete({ commit }, newAthlete: Athlete) {
    commit('SETUP_CREATE_ATHLETE', newAthlete);
  },
  createAthlete({ dispatch, commit }, athlete: Athlete) {
    axios
      .post('/api/athlete/create', athlete)
      .then((res: AxiosResponse<{ data: Athlete }>) => {
        commit('FETCH_ATHLETE', res.data.data);

        //update search asynchronously
        void dispatch('fetchSearch');
      })
      .catch((err) => {
        notifyOfUnknownError(err);
      });
  },
  updateAthlete({ dispatch, commit }, athlete: Athlete) {
    axios
      .put(`/api/athlete/update/${encodeURIComponent(athlete.id)}`, athlete)
      .then((res: AxiosResponse<{ data: Athlete }>) => {
        commit('FETCH_ATHLETE', res.data.data);

        //update search and favourites asynchronously
        void dispatch('fetchSearch');
        void dispatch('fetchFavourites');
      })
      .catch((err) => {
        notifyOfUnknownError(err);
      });
  },
  updateAthleteNotes({}, athlete: Athlete) {
    axios
      .put(`/api/athlete/update_notes/${encodeURIComponent(athlete.id)}`, {
        notes: athlete.notes,
      })
      .then(() => {
        // notes are changed locally, not update event needed
        // an update on typing events risks inconsistency
      })
      .catch((err) => {
        notifyOfUnknownError(err);
      });
  },
  updateAthletePerformances({ dispatch }, athlete: Athlete) {
    axios
      .put(
        `/api/athlete/update_performances/${encodeURIComponent(athlete.id)}`,
        {
          performances: JSON.stringify(athlete.performances),
        }
      )
      .then(() => {
        // performances are changed locally, not update event needed

        //update search and favourites asynchronously
        void dispatch('fetchSearch');
        void dispatch('fetchFavourites');
      })
      .catch((err) => {
        notifyOfUnknownError(err);
      });
  },
  deleteAthlete({ commit }, athlete: Athlete) {
    axios
      .delete(`/api/athlete/delete/${encodeURIComponent(athlete.id)}`)
      .then(() => {
        commit('DELETE_ATHLETE');
      })
      .catch((err) => {
        notifyOfUnknownError(err);
      });
  },
  getYear({ dispatch, commit }) {
    axios
      .get<YearResource>('/api/year/get')
      .then((res) => {
        commit('UPDATE_ALL_YEARS_ARRAY', res.data);
        commit('UPDATE_CURRENT_YEAR', res.data.current);

        //update search asynchronously
        void dispatch('fetchSearch');
      })
      .catch((err) => {
        notifyOfUnknownError(err);
      });
  },
  setYear({ dispatch, commit }, year: number) {
    axios
      .put('/api/year/set', { year: year })
      .then((res) => {
        commit('UPDATE_CURRENT_YEAR', res.data);

        //update search asynchronously
        void dispatch('fetchSearch');
        void dispatch('fetchFavourites');
      })
      .catch((err) => {
        notifyOfUnknownError(err);
      });
  },
  fetchSearch({ commit, state }, searchString: string) {
    if (searchString === undefined) {
      //call has no searchString specified, use previous one
    } else {
      //call has searchString specified, set the stored value new
      commit('SET_SEARCH_STRING', searchString);
    }

    if (state.searchString == '') {
      //search string is empty. an empty string produces no results
      commit('FETCH_ATHLETE_SEARCH', []);
    } else {
      //a string is set, therefore make the call
      axios
        .get(`api/search/athletes/${encodeURIComponent(state.searchString)}`)
        .then((res: AxiosResponse<{ athletes: Athlete[] }>) => {
          commit('FETCH_ATHLETE_SEARCH', res.data.athletes);

          // Notify the user of the fetch
          Notify.create({
            type: 'reloaded',
            message: i18n.global.t('general.reloadedSearch'),
          });
        })
        .catch((err) => {
          notifyOfUnknownError(err);
        });
    }
  },
  requestPDF({}, htmlString: string) {
    axios
      .put(
        'api/pdf/generate_from_html',
        { htmlString: htmlString },
        { responseType: 'blob' }
      )
      .then((res) => {
        const status = exportFile('List.pdf', res.data);

        if (status === true) {
          // browser allowed it
        } else {
          // browser denied it
          notifyOfUnknownError(status);
        }
      })
      .catch((err) => {
        notifyOfUnknownError(err);
      });
  },
};

export default actions;

function notifyOfUnknownError(err: unknown) {
  console.error(err);

  // Notify the user of the error
  Notify.create({
    type: 'error',
    message: i18n.global.t('general.unknownError'),
  });
}
