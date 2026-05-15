import { createI18n } from 'vue-i18n';
import es from './es.json';
import de from './de.json';
import en from './en.json';

const savedLocale = localStorage.getItem('locale') || 'es';

const i18n = createI18n({
    legacy: false,
    locale: savedLocale,
    fallbackLocale: 'es',
    messages: { es, de, en },
});

export default i18n;
