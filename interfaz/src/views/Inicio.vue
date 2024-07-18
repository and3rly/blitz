<template>
  <div class="container text-center mt-5">
    <h3> ¡Bienvenido {{usuario.nombre }} {{ usuario.apellido }}!</h3>
    
    <img 
      src="@/assets/img/inicio.png" 
      class="img-fluid" 
      style="width: 350px;"
    >
  </div>
</template>

<script>
import { useLoginStore } from "@/stores/app-login";

  export default {
    data: () => ({
      usuario: null
    }),
    created() {
      this.usuario = useLoginStore().usuario
    }
  }
</script>