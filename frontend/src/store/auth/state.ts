export interface AuthStateInterface {
  isLoggedIn: boolean;
}

function state(): AuthStateInterface {
  return {
    isLoggedIn: false
  };
}

export default state;
