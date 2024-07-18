import axios from "axios";
import router from "../router/index.ts";
//import { useLoginStore } from "@/stores/app-login";

const axiosClient = axios.create({
    baseURL: import.meta.env.VITE_API_BASE_URL
})

axiosClient.interceptors.response.use(response => {
    return response;
}, error => {
    if (error.response.status === 401) {
        //localStorage.removeItem('token')
        router.push({name: 'Login'})
    } else {
        return new Promise((resolve,reject) => {
            if (error.response.status === 500) {
                reject('Estamos teniendo inconvenientes con el servidor, inténtalo más tarde.');
            } else if (error.response.status === 404) {
                reject('No se encontró el servicio solicitado, inténtalo más tarde.');
            } else {
                reject(error);
            }
        });
    }
    throw error;
})

export default axiosClient;