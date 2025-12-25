import axios from 'axios';

const tokenKey = 'jwt_token';

export const getToken = () => localStorage.getItem(tokenKey);
export const setToken = (t) => localStorage.setItem(tokenKey, t);
export const clearToken = () => localStorage.removeItem(tokenKey);

export const api = axios.create({
  baseURL: '/',
});

api.interceptors.request.use((config) => {
  const token = getToken();
  if (token) {
    config.headers.Authorization = `Bearer ${token}`;
  }
  return config;
});

