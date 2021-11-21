import axios, { AxiosError, AxiosResponse } from 'axios';
import { ActionTree } from 'vuex';
import { StateInterface } from '../index';
import { AuthStateInterface } from './state';
import { i18n } from './../../boot/i18n';

enum StatusType {
  success = 'success',
  error = 'error',
}
export interface Status {
  type: StatusType;
  message: string;
}

const actions: ActionTree<AuthStateInterface, StateInterface> = {
  checkAuthenticated: async function ({ commit }) {
    let status = false;

    await axios
      .get('api/user/get')
      .then((response: { data: unknown }) => {
        if (response.data != null) {
          commit('registerUser', response.data);
          status = true;
        } else {
          commit('unregisterUser');
        }
      })
      .catch((error) => {
        console.error(error);
        commit('unregisterUser');
      });

    return status;
  },
  checkCORSCookies: async function () {
    try {
      return await axios.get('sanctum/csrf-cookie');
    } catch (err) {
      return console.error(err);
    }
  },
  login: async function (
    { dispatch },
    { password, email }: { password: string; email: string }
  ) {
    const status = [] as Status[];

    await axios
      .post('login', { email: email, password: password })
      .then(async () => {
        const ret = (await dispatch('checkAuthenticated').catch((error) => {
          console.log(error);
        })) as boolean;

        if (ret) {
          status.push({
            type: StatusType.success,
            message: i18n.global.t('auth.loggedIn'),
          });
        }
      })
      .catch((error: AxiosError<{ errors: { email: string[] } }>) => {
        if (error.response) {
          status.push({
            type: StatusType.error,
            message: error.response.data.errors.email[0],
          });
        }
        console.error(error);
      });

    return status;
  },
  requestReset: async function ({}, { email }: { email: string }) {
    const status = [] as Status[];

    await axios
      .post('forgot-password', { email: email })
      .then((response: AxiosResponse<{ message: string }>) => {
        status.push({
          type: StatusType.success,
          message: response.data.message,
        });
      })
      .catch((error: AxiosError<{ errors: { email: string[] } }>) => {
        if (error.response) {
          status.push({
            type: StatusType.error,
            message: error.response.data.errors.email[0],
          });
        }
        console.error(error);
      });

    return status;
  },
  setPassword: async function (
    {},
    {
      password,
      passwordConfirmation,
      email,
      token,
    }: {
      password: string;
      passwordConfirmation: string;
      email: string;
      token: string;
    }
  ) {
    const status = [] as Status[];

    await axios
      .post('reset-password', {
        email: email,
        password: password,
        password_confirmation: passwordConfirmation,
        token: token,
      })
      .then((response: AxiosResponse<{ message: string }>) => {
        status.push({
          type: StatusType.success,
          message: response.data.message,
        });
      })
      .catch((error: AxiosError<{ errors: { email: string[] } }>) => {
        if (error.response) {
          status.push({
            type: StatusType.error,
            message: error.response.data.errors.email[0],
          });
        }
        console.error(error);
      });

    return status;
  },
  logout: async function ({ commit }) {
    const status = [] as Status[];

    await axios
      .post('logout')
      .then(() => {
        commit('unregisterUser');
        status.push({
          type: StatusType.success,
          message: i18n.global.t('auth.loggedOut'),
        });
      })
      .catch((error) => {
        console.log(error);
      });

    return status;
  },
};

export default actions;
