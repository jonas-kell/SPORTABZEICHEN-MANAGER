import { MutationTree } from 'vuex';
import { AuthStateInterface, User } from './state';
import { IsLoggedInStorageKey, UserStorageKey } from './state';
import { SessionStorage } from 'quasar';

const mutation: MutationTree<AuthStateInterface> = {
  registerUser(state: AuthStateInterface, newUser: User) {
    state.user = newUser;
    state.isLoggedIn = true;

    SessionStorage.set(UserStorageKey, state.user);
    SessionStorage.set(IsLoggedInStorageKey, state.isLoggedIn);
  },
  unregisterUser(state: AuthStateInterface) {
    state.user = null;
    state.isLoggedIn = false;

    SessionStorage.set(UserStorageKey, state.user);
    SessionStorage.set(IsLoggedInStorageKey, state.isLoggedIn);
  },
};

export default mutation;
