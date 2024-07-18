<template>
  <div class="content-login"> 
    <div class="d-flex justify-content-center align-items-center">
      <div class="card p-2 shadow rounded-2 content-form">
        <div class="text-center mt-4">
          <img 
            src="@/assets/img/logo.jpg"
            class="img-fluid logo-emp"
          >
        </div>
        <div class="card-body">
          <div class="alert alert-danger p-3 mb-3" v-if="mensaje != null">
            <i class="fa fa-info-circle me-2"></i>{{ mensaje }}
          </div>

          <form @submit.prevent="login">
            <div class="mb-3 mt-2">
              <label 
                for="inputUsuario" class="form-label fw-bold mb-1">
                  <i class="fa fa-user me-2"></i>Usuario:
                </label>
              <input 
                type="text" 
                class="form-control" 
                id="inputUsuario"
                placeholder="Ingrese usuario" 
                v-model="form.usuario"
              >
            </div>

            <div class="mb-4">
              <label for="inputClave" class="form-label fw-bold mb-1">
                <i class="fa fa-key me-1"></i>Contraseña:
              </label>
              <input 
                type="password" 
                class="form-control" 
                id="inputClave"
                placeholder="Ingrese contraseña" 
                v-model="form.clave"
              >
            </div>

            <div class="d-grid gap-2">
              <button 
                class="btn btn-primary" 
                type="submit"
                @click="login"
                :disabled="btnIniciar"
              >  
                <span v-if="btnIniciar === false">Iniciar sesión</span>

                <template v-else>
                  <span class="spinner-grow spinner-grow-sm me-2" aria-hidden="true"></span>
                  <span role="status">Iniciando...</span>    
                </template>          
              </button>
            </div>
          </form>
        </div>
      </div>
    </div> 
  </div>
</template>

<script>
  import { useLoginStore } from "@/stores/app-login";

  export default {
    name: "Login",
    data: () => ({
      btnIniciar: false,
      mensaje: null,
      form: {}
    }),
    methods: {
      login() {
        this.btnIniciar = true 
        this.mensaje = null 

        let store = useLoginStore();

        store.login(this.form)
        .then(res => {    
          this.btnIniciar = false

          if (store.isLoggedIn) {
            this.$router.push({name: "Inicio"});
          } else {
            this.mensaje = res.mensaje
          }
        })
      }
    }
  }
</script>

<style>
  .content-login {
    width: 100%;
    height: 100vh;
    background-size: 100%;
    /*background-image: url("@/assets/imagenes/fondo.jpg");*/
    display: flex;
    justify-content: center; /* Centrado horizontal */
    align-items: center; /* Centrado vertical */
    height: 100vh;

  }

  .content-form {
    width: 350px;
    height: 500px;  
  }

  .logo-emp {
    width: 300px;
  }

</style>