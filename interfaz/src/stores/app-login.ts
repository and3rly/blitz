import {defineStore} from "pinia"
import axiosCliente from "@/plugins/axios.js"

export const useLoginStore  = defineStore('login', {
  state: () => ({
    token: localStorage.getItem('token'),
    usuario: JSON.parse(localStorage.getItem('usuario')),
    urlBase: ""
  }),
  persist: {
    key: 'session'
  },
  actions: {
    login(data) {
      return new Promise((resolve, reject) => {
        axiosCliente.defaults.headers.common['usuario'] = data.usuario;
        axiosCliente.defaults.headers.common['clave']   = data.clave;

        axiosCliente.post(`${this.urlBase}/sesion/login`, data)
        .then(res => {

          if (res.data.exito) {
            this.usuario = res.data.usuario
            this.token = res.data.token
          }
          
          resolve(res.data);

        }).catch(e => {
          reject(e);
        });
      })  
    },
    async logout() {
      await axiosCliente.post(`${this.urlBase}/sesion/logout`)
      .then (res => {
        if (res.data.exito) {
          this.token = null;
          this.usuario = null;

          delete axiosCliente.defaults.headers.common['Authorization']
        }
      });
    },
    async validaToken () {
      const storeLogin = useLoginStore();

      if (this.token) {
        await axiosCliente.post(`${this.urlBase}/sesion/validar_token`, {token: this.token})
        .then(res => {

          if (res.data.valido) {
            axiosCliente.defaults.headers.common['Authorization'] = this.token;
          }

        }).catch(e => {
          if (e.response.data) {
            if (e.response.status == 401) {
              storeLogin.logout()
              delete axiosCliente.defaults.headers.common['Authorization'];
            }
          }
        });
      }
    }
  },
  getters: {
    isLoggedIn: state => !!state.token
  }
})