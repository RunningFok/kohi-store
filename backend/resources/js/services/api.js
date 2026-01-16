import axios from 'axios';

axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
axios.defaults.headers.common['Accept'] = 'application/json';

const token = document.head.querySelector('meta[name="csrf-token"]');
if (token) {
    axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
}

const API_BASE_URL = '/api';

const api = {
    getProducts: () => axios.get(`${API_BASE_URL}/products`),
    getProduct: (id) => axios.get(`${API_BASE_URL}/products/${id}`),
    
    register: (data) => axios.post(`${API_BASE_URL}/customers/register`, data),
    login: (data) => axios.post(`${API_BASE_URL}/customers/login`, data),
    logout: () => axios.post(`${API_BASE_URL}/customers/logout`),
    getCurrentCustomer: () => axios.get(`${API_BASE_URL}/customers/me`),
};

export const setAuthToken = (token) => {
    if (token) {
        axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;
        localStorage.setItem('auth_token', token);
    } else {
        delete axios.defaults.headers.common['Authorization'];
        localStorage.removeItem('auth_token');
    }
};

const savedToken = localStorage.getItem('auth_token');
if (savedToken) {
    setAuthToken(savedToken);
}

export default api;
