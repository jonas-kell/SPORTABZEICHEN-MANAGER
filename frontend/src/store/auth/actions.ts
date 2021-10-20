import axios from 'axios';
import { ActionTree } from 'vuex';
import { StateInterface } from '../index';
import { AuthStateInterface } from './state';

const actions: ActionTree<AuthStateInterface, StateInterface> = {
  checkAuthenticated({ commit }) {
    return axios
      .get('api/user/get')
      .then((response: { data: unknown }) => {
        if (response.data != null) {
          commit('registerUser', response.data);
        } else {
          commit('unregisterUser');
        }
      })
      .catch(error => {
        console.error(error);
        commit('unregisterUser');
      });
  },
  checkCORSCookies() {
    return axios.get('sanctum/csrf-cookie').catch(err => console.error(err));
  },
  login(
    { dispatch },
    { password, email }: { password: string; email: string }
  ) {
    return axios
      .post('login', { email: email, password: password })
      .then(() => {
        dispatch('checkAuthenticated').catch(error => {
          console.log(error);
        });
      })
      .catch(error => {
        console.log(error);
      });
  },
  requestReset({}, { email }: { email: string }) {
    return axios.post('forgot-password', { email: email }).catch(error => {
      console.log(error);
    });
  },
  setPassword(
    { dispatch },
    {
      password,
      passwordConfirmation,
      email,
      token
    }: {
      password: string;
      passwordConfirmation: string;
      email: string;
      token: string;
    }
  ) {
    return axios
      .post('reset-password', {
        email: email,
        password: password,
        password_confirmation: passwordConfirmation,
        token: token
      })
      .then(() => {
        dispatch('checkAuthenticated').catch(error => {
          console.log(error);
        });
      })
      .catch(error => {
        console.log(error);
      });
  },
  logout({ commit }) {
    return axios
      .post('logout')
      .then(() => {
        commit('unregisterUser');
      })
      .catch(error => {
        console.log(error);
      });
  }
};

export default actions;
