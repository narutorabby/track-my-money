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
app.use(vue3GoogleLogin, { clientId: '428260765707-a4h0cimp8d5leak29g0adkpa76bhkpv6.apps.googleusercontent.com' });
app.use(naive);

app.mount("#app");