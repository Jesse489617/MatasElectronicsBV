import axios from 'axios';
import { client } from 'laravel-precognition-vue';

axios.defaults.headers.common['Accept'] = 'application/json';

axios.interceptors.request.use((config) => {
    const token = localStorage.getItem('token');

    if (token) {
        config.headers.Authorization = `Bearer ${token}`;
    }

    return config;
});

client.use(axios);

export default axios;
