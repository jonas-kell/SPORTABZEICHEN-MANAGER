import { boot } from 'quasar/wrappers';
import { createI18n } from 'vue-i18n';

import messages from 'src/i18n';

export const i18n = createI18n({
  locale: 'de',
  globalInjection: true,
  messages,
});

export default boot(({ app }) => {
  // Set i18n instance on app
  app.use(i18n);

  app.config.globalProperties.$t = i18n.global.t;
  // ^ ^ ^ this will allow you to use this.$t (for Vue Options API form)
  //       so you won't necessarily have to import i18n in each vue file
});
