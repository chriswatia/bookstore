import 'bootstrap/dist/css/bootstrap.css';
import 'bootstrap/dist/js/bootstrap.bundle.min.js'
import { createApp } from 'vue';
import App from './App.vue';


import axios from 'axios';
import router from './router';
import store from './store';

const app = createApp(App);
app.use(router);
app.use(store);

// Base URL
axios.defaults.baseURL = 'http://127.0.0.1:8000/api/v1';

// Make Axios globally in the Vue app
app.config.globalProperties.$axios = axios;

app.mount('#app');



