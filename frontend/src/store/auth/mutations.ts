import { MutationTree } from 'vuex';
import { AuthStateInterface, User } from './state';

const mutation: MutationTree<AuthStateInterface> = {
  registerUser(state: AuthStateInterface, newUser: User) {
    state.user = newUser;
    state.isLoggedIn = true;
  },
  unregisterUser(state: AuthStateInterface) {
    state.isLoggedIn = false;
    state.user = null;
  }
};

export default mutation;
