<template>
  <form @submit.prevent="guardar">
    <div class="row mb-1">
      <label 
        for="inputNombre" 
        class="form-label fw-bold col-sm-3 text-end"
      >
        Nombre: <span class="text-danger">*</span>
      </label>
      <div class="col-sm-8">
        <input 
          type="text" 
          class="form-control" 
          id="inputNombre"
          v-model="form.nombre"
          required
        >
      </div>
    </div>

    <div class="row mb-1">
      <label 
        for="inputApellido" 
        class="form-label fw-bold col-sm-3 text-end"
      >
        Apellido: <span class="text-danger">*</span>
      </label>
      <div class="col-sm-8">
        <input 
          type="text" 
          class="form-control" 
          id="inputApellido"
          v-model="form.apellido"
          required
        >
      </div>
    </div>

    <div class="row mb-1">
      <label 
        for="inputUsuario" 
        class="form-label fw-bold col-sm-3 text-end"
      >
        Usuario: <span class="text-danger">*</span>
      </label>
      <div class="col-sm-8">
        <input 
          type="text" 
          class="form-control" 
          id="inputUsuario"
          v-model="form.usuario"
        >
      </div>
    </div>

    <div class="row mb-1" v-if="reg === ''">
      <label 
        for="inputPassword" 
        class="form-label fw-bold col-sm-3 text-end"
      >
        Contraseña: <span class="text-danger">*</span>
      </label>
      <div class="col-sm-8">
        <div class="input-group">
          <input 
            :type="verClave ? 'text' : 'password'" 
            autocomplete="new-password"
            class="form-control"
            id="inputPassword"
            maxlength="10"
            v-model="form.clave"
          >
          <button 
            class="btn btn-outline-secondary" 
            type="button" 
            @click="verClave = !verClave"
          >
            <i :class="verClave ? 'bi bi-eye-slash' : 'bi bi-eye'"></i>
          </button>
        </div>
        <div id="passwordHelpBlock" class="form-text">
          Su contraseña debe tener entre 8 y 10 caracteres de longitud, contener letras y números y no debe contener espacios.
        </div>
      </div>
    </div>

    <div class="row mb-1">
      <label 
        id="selectDepartamento" 
        class="form-label fw-bold col-sm-3 text-end"
      >
        Rol: <span class="text-danger">*</span>
      </label>
      <div class="col-sm-8">
        <vue-select 
          id="selectDepartamento"
          :options="roles"
          :reduce="r => r.value"
          placeholder="------------"
          v-model="form.rol_id"
        >  
        </vue-select>
      </div>
    </div>

    <div class="row mb-1">
      <label 
        for="inputCorreo" 
        class="form-label fw-bold col-sm-3 text-end"
      >
        Correo:
      </label>
      <div class="col-sm-8">
        <input 
          type="text" 
          class="form-control" 
          id="inputCorreo"
          v-model="form.correo"
        >
      </div>
    </div>

    <div class="row col-sm-8 offset-sm-3 mt-1" v-if="reg !== ''">
      <div class="form-check form-switch">
        <input 
          class="form-check-input" 
          type="checkbox" 
          role="switch" 
          id="flexSwitchCheckChecked"
          :true-value="1" 
          :false-value="0"
          v-model="form.activo"
        >
        <label class="form-check-label" for="flexSwitchCheckChecked">Activo</label>
      </div>
    </div>

    <div class="col-sm-12 text-end mt-4">
      <!--button 
        v-if="reg !== ''"
        type="button" 
        class="btn btn-secondary me-2" 
        @click="nuevo"
      >
        Cancelar / Nuevo
      </button-->

      <button type="submit" class="btn btn-primary" :disabled="btnGuardar">
        <span v-if="btnGuardar === false">
          <i class="fa fa-save me-1"></i> Guardar
        </span>

        <template v-else>
          <span class="spinner-grow spinner-grow-sm me-2" aria-hidden="true"></span>
          <span role="status">Guardando...</span>    
        </template>  
      </button>
    </div>

  </form>
</template>

<script>
  import Blitz from "@/mixins/Blitz.js"

  export default {
    emits: ["limpiar", "actualizar"],
    mixins: [Blitz],
    props: {
      usuario: {
        type: Object,
        default: null
      }
    },
    data: () => ({
      cat: {},
      verClave: false
    }),
    created() {
      this.autoBuscar = false
      this._emit = true
      this.url = "usuario"

      if (this.usuario == null) {
        this.fbase = {
          rol_id: "",
          activo: 1
        }
      } else {
        this.setDataForm(this.usuario)
      }

      this.getDatos()
    },
    methods: {
      getDatos() {
        this.inicio = true

        this.$http
        .get(`${this.$baseUrl}/usuario/get_datos`)
        .then(res => {

          this.inicio = false
          this.cat = res.data.cat

        }).catch(e => {
          this.inicio = false
          console.log(e)
        })
      },
    },
    computed: {
      roles() {
        if (this.cat.roles) {
          return this.setDatoSelect(this.cat.roles, "id", "nombre")
        } 

        return []
      },
    }
  }
  
</script>