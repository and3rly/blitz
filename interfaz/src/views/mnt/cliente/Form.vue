<template>
  <form @submit.prevent="guardar">
    <div class="row mb-1">
      <label 
        for="inputCodigo" 
        class="form-label fw-bold col-sm-3 text-end"
      >
        Código:
      </label>
      <div class="col-sm-8">
        <input 
          type="text" 
          class="form-control" 
          id="inputCodigo"
          v-model="form.codigo"
        >
      </div>
    </div>

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
        for="inptuRazonSocial" 
        class="form-label fw-bold col-sm-3 text-end"
      >
        Razón social: <span class="text-danger">*</span>
      </label>
      <div class="col-sm-8">
        <input 
          type="text" 
          class="form-control" 
          id="inptuRazonSocial"
          v-model="form.razon_social"
        >
      </div>
    </div>

    <div class="row mb-1">
      <label 
        for="inputIdentificacion" 
        class="form-label fw-bold col-sm-3 text-end"
      >
        Identificación:
      </label>
      <div class="col-sm-8">
        <div class="form-check form-check-inline" v-for="i in cat.tdocumentos">
          <input 
            class="form-check-input" 
            type="radio" 
            name="inlineRadioOptions" 
            :id="'chkTipoDoc'+i.id" 
            :value="i.id"
            v-model="form.tipo_documento_id"
          >
          <label class="form-check-label" :for="'chkTipoDoc'+i.id">{{ i.nombre }}</label>
        </div>

        <input 
          type="text" 
          class="form-control" 
          id="inputIdentificacion"
          v-model="form.identificacion"
        >
      </div>
    </div>

    <div class="row mb-1">
      <label 
        id="selectDepartamento" 
        class="form-label fw-bold col-sm-3 text-end"
      >
        Departamento:
      </label>
      <div class="col-sm-8">
        <vue-select 
          id="selectDepartamento"
          :options="departamentos"
          :reduce="r => r.value"
          placeholder="------------"
          v-model="form.departamento_id"
        >  
        </vue-select>
      </div>
    </div>

    <div class="row mb-1">
      <label 
        id="selectMunicipio" 
        class="form-label fw-bold col-sm-3 text-end"
      >
        Municipio:
      </label>
      <div class="col-sm-8">
        <vue-select
          id="selectMunicipio"
          :options="municipios"
          :reduce="r => r.value"
          placeholder="------------"
          v-model="form.municipio_id"
        >  
        </vue-select>
      </div>
    </div>

    <div class="row mb-1">
      <label 
        for="inputDireccion" 
        class="form-label fw-bold col-sm-3 text-end"
      >
        Dirección:
      </label>
      <div class="col-sm-8">
        <input 
          type="text" 
          class="form-control" 
          id="inputDireccion"
          v-model="form.direccion"
        >
      </div>
    </div>

    <div class="row mb-1">
      <label 
        for="inputTelefono" 
        class="form-label fw-bold col-sm-3 text-end"
      >
        Teléfono:
      </label>
      <div class="col-sm-8">
        <input 
          type="text" 
          class="form-control" 
          id="inputTelefono"
          v-model="form.telefono"
        >
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
      <button 
        v-if="reg !== ''"
        type="button" 
        class="btn btn-secondary me-2" 
        @click="nuevo"
      >
        Cancelar / Nuevo
      </button>

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
    mixins: [Blitz],
    props: {
      cliente: {
        type: Object,
        default: null
      }
    },
    data: () => ({
      cat: {}
    }),
    created() {
      this.autoBuscar = false
      this._emit = true
      this.url = "mnt/cliente"

      if (this.cliente == null) {
        this.fbase = {
          tipo_documento_id: 1,
          departamento_id: "",
          municipio_id: "",
          activo: 1
        }
      } else {
        this.setDataForm(this.cliente)
      }

      this.getDatos()
    },
    methods: {
      getDatos() {
        this.inicio = true

        this.$http
        .get(`${this.$baseUrl}/mnt/cliente/get_datos`)
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

          this.form.municipio_id = (this.cliente && this.form.departamento_id === this.cliente.departamento_id) ? this.cliente.municipio_id : ""

          return this.setDatoSelect(tmpMun, "id", "nombre")
        } 

        return []
      }
    }
  }
</script>