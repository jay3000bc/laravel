import { createApp } from 'vue'
import App from './App.vue'
import router from './router'

import './assets/tailwind.css';

import axios from 'axios';
import VueCookies from 'vue-cookies';

// axios.defaults.baseURL = 'http://127.0.0.1:8000/api/';
axios.defaults.baseURL = 'https://www.alegralabs.com/banking/backend/public/api/';


const app = createApp(App)

app.use(router)

// Add an Axios request interceptor
axios.interceptors.request.use(
    (config) => {
      const token = VueCookies.get('token');
      if (token) {
        config.headers['Authorization'] = `Bearer ${token}`;
      }
      return config;
    },
    (error) => {
      return Promise.reject(error);
    }
  );

app.mount('#app')
