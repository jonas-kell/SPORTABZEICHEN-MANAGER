import { createI18n, I18n } from 'vue-i18n';
import messages from 'src/i18n';

export default ({ app }: { app: { use: (param: I18n) => void } }) => {
  // Create I18n instance
  const i18n = createI18n({
    locale: 'de',
    messages,
  });

  // Tell app to use the I18n instance
  app.use(i18n);
};
