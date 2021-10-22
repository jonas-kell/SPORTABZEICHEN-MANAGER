import axios, { AxiosResponse } from 'axios';
import { ActionTree } from 'vuex';
import { StateInterface } from '../index';
import FileDownload from 'js-file-download';
import { AthletesStateInterface, Athlete, Year } from './state';

const actions: ActionTree<AthletesStateInterface, StateInterface> = {
  fetchFavourites({ commit }) {
    axios
      .get('api/favourites')
      .then((res: AxiosResponse<{ athletes: Athlete[] }>) => {
        commit('FETCH_FAVOURITES', res.data.athletes);
      })
      .catch((err) => {
        console.log(err);
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
        console.log(err);
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
        console.log(err);
      });
  },
  fetchAthlete({ commit }, athlete_id: number) {
    axios
      .get(`/api/athlete/${encodeURIComponent(athlete_id)}`)
      .then((res: AxiosResponse<{ data: Athlete }>) => {
        commit('FETCH_ATHLETE', res.data.data);
      })
      .catch((err) => {
        console.log(err);
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
        console.log(err);
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
        console.log(err);
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
        console.log(err);
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
        console.log(err);
      });
  },
  deleteAthlete({ commit }, athlete: Athlete) {
    axios
      .delete(`/api/athlete/delete/${encodeURIComponent(athlete.id)}`)
      .then(() => {
        commit('DELETE_ATHLETE');
      })
      .catch((err) => {
        console.log(err);
      });
  },
  getYear({ dispatch, commit }) {
    axios
      .get('/api/year/get')
      .then((res) => {
        commit('UPDATE_YEARS_ARRAY', res.data);

        //update search asynchronously
        void dispatch('fetchSearch');
      })
      .catch((err) => {
        console.log(err);
      });
  },
  setYear({ dispatch, commit }, year: Year) {
    axios
      .put('/api/year/set', { year: year })
      .then((res) => {
        commit('UPDATE_YEARS_ARRAY', res.data);

        //update search asynchronously
        void dispatch('fetchSearch');
        void dispatch('fetchFavourites');
      })
      .catch((err) => {
        console.log(err);
      });
  },
  fetchSearch({ commit, state }, searchString: string) {
    if (searchString === undefined) {
      //call has no searchString specified, use previous one
    } else {
      //call has searchString specified, set the stored value new
      state.searchString = searchString;
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
        })
        .catch((err) => {
          console.log(err);
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
        FileDownload(res.data, 'List.pdf');
      })
      .catch((err) => {
        console.log(err);
      });
  },
};

export default actions;
