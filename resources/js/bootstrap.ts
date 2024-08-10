import axios from 'axios';
import 'moment/locale/es';
import moment from 'moment-timezone';

window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

// Permitir el envío de cookies en cada petición
window.axios.defaults.withCredentials = true;

// Interceptor para incluir el token CSRF en cada petición
window.axios.interceptors.request.use(config => {
    // Agregar token de autenticación
    const authToken = localStorage.getItem('auth_token');
    if (authToken) {
        config.headers['Authorization'] = `Bearer ${authToken}`;
    }

    return config;
}, error => {
    return Promise.reject(error);
});

moment.locale('es-mx')