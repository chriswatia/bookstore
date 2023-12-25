import axios from 'axios';

const BASE_URL = '/books';

// Interceptor to add the Authorization header to each request
axios.interceptors.request.use(
  (config) => {
    const token = localStorage.getItem('authToken');
    if (token) {
      config.headers.Authorization = `Bearer ${token}`;
    }
    return config;
  },
  (error) => {
    return Promise.reject(error);
  }
);

export default {
  getAllBooks() {
    // return axios.get(BASE_URL);
    return axios.get(BASE_URL);
  }
};
