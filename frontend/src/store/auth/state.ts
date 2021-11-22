import { SessionStorage } from 'quasar';

export const UserStorageKey = 'THEUSERTOSTOREINSESSIONSTORAGE';
export const IsLoggedInStorageKey = 'ISLOGGEDINSESSIONSTORAGE';

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
  if (SessionStorage.getItem(IsLoggedInStorageKey)) {
    isLoggedIn = true;
    user = SessionStorage.getItem(UserStorageKey);
  }
} catch (e) {}

function state(): AuthStateInterface {
  return {
    isLoggedIn: isLoggedIn,
    user: user,
  };
}

export default state;
