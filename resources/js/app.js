import { createApp } from 'vue';
import router from './router';
import axios from 'axios';

axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

const app = createApp({});

app.use(router);

app.mount('#app');
