import { SessionStorage } from 'quasar';

export const USER_STORAGE_KEY = 'THEUSERTOSTOREINSESSIONSTORAGE';
export const IS_LOGGED_IN_STORAGE_KEY = 'ISLOGGEDINSESSIONSTORAGE';

export interface AuthStateInterface {
  isLoggedIn: boolean;
  user: null | User;
}

export interface User {
  name: string;
  email: string;
  id: number;
  year: string;
}

let isLoggedIn = false;
let user = null as null | User;
// cache user, to get smoother page refresh
try {
  if (SessionStorage.getItem(IS_LOGGED_IN_STORAGE_KEY)) {
    isLoggedIn = true;
    user = SessionStorage.getItem(USER_STORAGE_KEY);
  }
} catch (e) {}

function state(): AuthStateInterface {
  return {
    isLoggedIn: isLoggedIn,
    user: user,
  };
}

export default state;
