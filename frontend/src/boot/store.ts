import { boot } from 'quasar/wrappers';
import store from './../store';

export default boot(({ app }) => {
  // Inject Vuex store globally into Vue instance
  app.use(store);
});
