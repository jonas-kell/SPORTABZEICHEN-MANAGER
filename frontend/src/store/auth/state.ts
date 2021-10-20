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

function state(): AuthStateInterface {
  return {
    isLoggedIn: false,
    user: null as null | User
  };
}

export default state;
