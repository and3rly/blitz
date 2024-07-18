import { defineStore } from "pinia";
import axiosCliente from "@/plugins/axios.js" 

export const useAppSidebarMenuStore = defineStore({
  id: "appSidebarMenu",
  state: () => ({
    modulos: null,
    urlBase: ""
  }),
  actions: {
    async get_menu() {
      await axiosCliente.get(`${this.urlBase}/modulo/get_modulos`)
      .then (res => {
        this.modulos = res.data.lista
      });
    }
  }
});

useAppSidebarMenuStore().get_menu();
