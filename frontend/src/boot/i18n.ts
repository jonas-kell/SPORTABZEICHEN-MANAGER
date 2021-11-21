import { createI18n, I18n } from 'vue-i18n';
import messages from 'src/i18n';

// Create I18n instance
export const i18n = createI18n({
  locale: 'de',
  messages,
});

export default ({ app }: { app: { use: (param: I18n) => void } }) => {
  // Tell app to use the I18n instance
  app.use(i18n);
};
