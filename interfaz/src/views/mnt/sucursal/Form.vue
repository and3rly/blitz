<template>
  <form @submit.prevent="guardar">
    <div class="row g-2">
      <div class="col-sm-6">
        <label 
          for="inputNombre" 
          class="form-label fw-bold mb-1"
        >
          Nombre: <span class="text-danger">*</span>
        </label>
        <input 
          type="text" 
          class="form-control" 
          id="inputNombre"
          v-model="form.nombre"
          required
        >
      </div>

      <div class="col-sm-6">
        <label 
          for="inptuRazonSocial" 
          class="form-label fw-bold mb-1"
        >
          Razón social:
        </label>
        <input 
          type="text" 
          class="form-control" 
          id="inptuRazonSocial"
          v-model="form.razon_social"
        >
      </div>

      <div class="col-sm-6">
        <label
          class="form-label fw-bold mb-1"
        >
          Departamento:
        </label>
        <vue-select 
          id="selectDepartamento"
          :options="departamentos"
          :reduce="r => r.value"
          placeholder="------------"
          v-model="form.departamento_id"
        >  
        </vue-select>
      </div>

      <div class="col-sm-6">
        <label
          class="form-label fw-bold mb-1"
        >
          Municipio:
        </label>
        <vue-select
          id="selectMunicipio"
          :options="municipios"
          :reduce="r => r.value"
          placeholder="------------"
          v-model="form.municipio_id"
        >  
        </vue-select>
      </div>

      <div class="col-sm-6">
        <label 
          for="inputDireccion" 
          class="form-label fw-bold mb-1"
        >
          Dirección:
        </label>
        <input 
          type="text" 
          class="form-control" 
          id="inputDireccion"
          v-model="form.direccion"
        >
      </div>

      <div class="col-sm-6">
        <label 
          for="inputTelefono" 
          class="form-label fw-bold mb-1"
        >
          Teléfono:
        </label>
        <input 
          type="text" 
          class="form-control" 
          id="inputTelefono"
          v-model="form.telefono"
        >
      </div>
    </div>

    <div class="col-sm-12 mt-2" v-if="reg !== ''">
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
    name: "FormEstablecimiento",
    mixins: [Blitz],
    props: {
      sucursal: {
        type: Object,
        default: null
      }
    },
    data: () => ({
      cat: []
    }),
    created() {
      this.url = "mnt/sucursal"
      this.autoBuscar = false
      this._emit = true
      this.getDatos()

      if (this.sucursal === null) {
        this.fbase = {
          departamento_id: "",
          municipio_id: "",
          activo: 1
        }
      } else {
        this.setDataForm(this.sucursal)
      }
    },
    methods: {
      getDatos() {
        this.inicio = true

        this.$http
        .get(`${this.$baseUrl}/mnt/sucursal/get_datos`)
        .then(res => {

          this.inicio = false
          this.cat = res.data.cat

        }).catch(e => {
          this.inicio = false
          console.log(e)
        })
      },
      nuevo() {
        this.$emit("limpiar")
        this.limpiar()
      }
    },
    computed: {
      departamentos() {
        if (this.cat.departamentos) {
          return this.setDatoSelect(this.cat.departamentos, "id", "nombre")
        } 

        return []
      },
      municipios() {
        if (this.cat.municipios && this.form.departamento_id) {

          let tmpMun = this.cat.municipios.filter(e => {
            return e.departamento_id == this.form.departamento_id
          })

          this.form.municipio_id = (this.sucursal && this.form.departamento_id === this.sucursal.departamento_id) ? this.sucursal.municipio_id : ""

          return this.setDatoSelect(tmpMun, "id", "nombre")
          
        } 

        return []
      }
    }
  }
</script>