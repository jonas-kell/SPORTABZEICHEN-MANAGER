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
  fetchRelevantAthletes({ commit, state }, loadRequirements: false) {
    axios
      .get(
        'api/relevant_athletes/' +
          String(state.currentYear) +
          '/' +
          String(loadRequirements ? 1 : 0)
      )
      .then((res: AxiosResponse<{ athletes: Athlete[] }>) => {
        commit('FETCH_RELEVANT_ATHLETES', res.data.athletes);

        // Notify the user of the fetch
        Notify.create({
          type: 'reloaded',
          message: i18n.global.t('general.reloadedRelevantAthletes'),
        });
      })
      .catch((err) => {
        notifyOfUnknownError(err);
      });
  },
  addFavourite({ commit }, athlete: Athlete) {
    axios
      .put(`api/favourites/add/${encodeURIComponent(athlete.id)}`)
      .then((res: AxiosResponse<{ athletes: Athlete[] }>) => {
        commit('FETCH_FAVOURITES', res.data.athletes);

        // Notify the user of the fetch
        Notify.create({
          type: 'reloaded',
          message: i18n.global.t('general.addedFavourite'),
        });
      })
      .catch((err) => {
        notifyOfUnknownError(err);
      });
  },
  dropFavourite({ commit }, athlete: Athlete) {
    axios
      .put(`api/favourites/drop/${encodeURIComponent(athlete.id)}`)
      .then((res: AxiosResponse<{ athletes: Athlete[] }>) => {
        commit('FETCH_FAVOURITES', res.data.athletes);

        // Notify the user of the fetch
        Notify.create({
          type: 'reloaded',
          message: i18n.global.t('general.removedFavourite'),
        });
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

        // Notify the user of the fetch
        Notify.create({
          type: 'reloaded',
          message: i18n.global.t('general.showAthlete'),
        });
      })
      .catch((err) => {
        notifyOfUnknownError(err);
      });
  },
  setupCreateAthlete({ commit }, newAthlete: Athlete) {
    commit('SETUP_CREATE_ATHLETE', newAthlete);
  },
  createAthlete(
    { dispatch, commit },
    params: { athlete: Athlete; cloned: false }
  ) {
    axios
      .post('/api/athlete/create', params.athlete)
      .then((res: AxiosResponse<{ data: Athlete }>) => {
        commit('FETCH_ATHLETE', res.data.data);

        // Notify the user of the action
        Notify.create({
          type: 'success',
          message: params.cloned
            ? i18n.global.t('general.clonedAthlete')
            : i18n.global.t('general.createdAthlete'),
        });

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

        // Notify the user of the action
        Notify.create({
          type: 'success',
          message: i18n.global.t('general.updatedAthleteDetails'),
        });

        //update search and favourites asynchronously
        void dispatch('fetchSearch');
        void dispatch('fetchFavourites');
      })
      .catch((err) => {
        notifyOfUnknownError(err);
      });
  },
  updateAthleteNotes({ commit }, athlete: Athlete) {
    axios
      .put(`/api/athlete/update_notes/${encodeURIComponent(athlete.id)}`, {
        notes: athlete.notes,
      })
      .then(() => {
        commit('FETCH_ATHLETE', athlete);

        // Notify the user of the change
        Notify.create({
          type: 'reloaded',
          message: i18n.global.t('general.updatedNotes'),
        });
      })
      .catch((err) => {
        notifyOfUnknownError(err);
      });
  },
  updateAthletePerformances(
    { dispatch },
    params: { athlete: Athlete; updateTable: false }
  ) {
    axios
      .put(
        `/api/athlete/update_performances/${encodeURIComponent(
          params.athlete.id
        )}`,
        {
          performances: JSON.stringify(params.athlete.performances),
        }
      )
      .then(() => {
        // Notify the user of the fetch
        Notify.create({
          type: 'reloaded',
          message: i18n.global.t('general.updatedPerformance'),
        });

        //update search and favourites and the cached center athlete asynchronously
        void dispatch('fetchSearch');
        void dispatch('fetchFavourites');
        if (!params.updateTable) {
          void dispatch('fetchAthlete', params.athlete.id);
        } else {
          void dispatch('fetchRelevantAthletes', true);
        }
      })
      .catch((err) => {
        notifyOfUnknownError(err);
      });
  },
  deleteAthlete({ commit, dispatch }, athlete: Athlete) {
    axios
      .delete(`/api/athlete/delete/${encodeURIComponent(athlete.id)}`)
      .then(() => {
        commit('DELETE_ATHLETE');

        // Notify the user of the action
        Notify.create({
          type: 'success',
          message: i18n.global.t('general.deletedAthlete'),
        });

        //update search and favourites asynchronously
        void dispatch('fetchSearch');
        void dispatch('fetchFavourites');
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

        // Notify the user of the fetch
        Notify.create({
          type: 'reloaded',
          message: i18n.global.t('general.changedYear'),
        });

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

    if (state.searchString == '' || searchString === null) {
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
  requestTablePDF({}, htmlString: string) {
    axios
      .put(
        'api/pdf/generate_table_from_html',
        { htmlString: htmlString },
        { responseType: 'blob' }
      )
      .then((res) => {
        const status = exportFile(
          'List_' + new Date().getTime() + '.pdf',
          res.data
        );

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
  requestOutputPDF({}, athleteIds: number[]) {
    axios
      .put(
        'api/pdf/generate_output_pdf',
        { athlete_ids: athleteIds },
        { responseType: 'blob' }
      )
      .then((res) => {
        const status = exportFile(
          'Output_' + new Date().getTime() + '.pdf',
          res.data
        );

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
