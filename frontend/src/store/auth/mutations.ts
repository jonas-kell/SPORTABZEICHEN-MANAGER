import { MutationTree } from 'vuex';
import { AuthStateInterface, User } from './state';
import { IS_LOGGED_IN_STORAGE_KEY, USER_STORAGE_KEY } from './state';
import { SessionStorage } from 'quasar';

const mutation: MutationTree<AuthStateInterface> = {
  registerUser(state: AuthStateInterface, newUser: User) {
    state.user = newUser;
    state.isLoggedIn = true;

    SessionStorage.set(USER_STORAGE_KEY, state.user);
    SessionStorage.set(IS_LOGGED_IN_STORAGE_KEY, state.isLoggedIn);
  },
  unregisterUser(state: AuthStateInterface) {
    state.user = null;
    state.isLoggedIn = false;

    SessionStorage.set(USER_STORAGE_KEY, state.user);
    SessionStorage.set(IS_LOGGED_IN_STORAGE_KEY, state.isLoggedIn);
  },
};

export default mutation;
