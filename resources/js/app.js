import { createApp } from 'vue';
import { createPinia } from 'pinia';
import App from './components/App.vue';
import router from './router';
import i18n from './i18n';
import axios from 'axios';

axios.defaults.withCredentials = true;
axios.defaults.withXSRFToken = true;

const app = createApp(App);
app.use(createPinia());
app.use(router);
app.use(i18n);
app.mount('#app');
