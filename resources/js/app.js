import './bootstrap';

import { createApp } from 'vue';

import App from './App.vue';
import router from './router';
import store from './store';
import vue3GoogleLogin from 'vue3-google-login';
import naive from 'naive-ui';

const app = createApp(App);
app.use(router);
app.use(store);
app.use(vue3GoogleLogin, { clientId: import.meta.env.VITE_GOOGLE_CLIENT_ID });
app.use(naive);

app.mount("#app");