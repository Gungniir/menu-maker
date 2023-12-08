import axios from 'axios';
import router from "@/router";

// Full config:  https://github.com/axios/axios#request-config
// axios.defaults.baseURL = process.env.baseURL || process.env.apiUrl || '';
// axios.defaults.headers.common['Authorization'] = AUTH_TOKEN;
// axios.defaults.headers.post['Content-Type'] = 'application/x-www-form-urlencoded';

const config = {
  baseURL: import.meta.env.VITE_API_URL
  // timeout: 60 * 1000, // Timeout
  // withCredentials: true, // Check cross-site Access-Control
};

const _axios = axios.create(config);
const __axios = axios.create(config);

_axios.interceptors.request.use(
  (cfg) => {
    // Do something before request is sent
    if (localStorage.getItem('jwt')) {
      cfg.headers['Authorization'] = 'Bearer ' + localStorage.getItem('jwt')
    }

    return cfg;
  },
  (err) => {
    // Do something with request error
    return Promise.reject(err);
  },
);

// Add a response interceptor
_axios.interceptors.response.use(
  (res) => {
    // Do something with response data
    return res;
  },
  async (err) => {
    // Do something with response error
    if (err.response.status === 403) {
      try {
        if (localStorage.getItem('jwt')) {
          const {data} = await __axios.post('/auth/refresh', {}, {
            headers: {
              Authorization: 'Bearer ' + localStorage.getItem('jwt')
            }
          });

          localStorage.setItem('jwt', data.access_token);
          err.config.headers.Authorization = 'Bearer ' + localStorage.getItem('jwt');
          return _axios.request(err.config);
        }
      } catch (e) {
        console.error(e);
      }

      router.push({
        path: '/login',
        query: {
          returnto: router.currentRoute.path
        }
      });
    }

    return Promise.reject(err);
  },
);

export default _axios;
