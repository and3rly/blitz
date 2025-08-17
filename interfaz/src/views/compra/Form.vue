<template>
  <div class="card shadow-sm">
    <div class="card-header">
      Datos Generales
    </div>
    <div class="card-body">
      <form id="formOc">
        <div class="row g-2">
          <div class="col-sm-4">
            <label for="selectProveedor" class="form-label fw-bold">
              No. Documento: <span class="text-danger">*</span>
            </label>
            <input 
              type="text" 
              class="form-control" 
              v-model="form.documento" 
              disabled
            >
            <div class="form-text">Se genera automáticamente.</div>
          </div>

          <div class="col-sm-4">
            <label for="selectProveedor" class="form-label fw-bold">
              Proveedor: <span class="text-danger">*</span>
            </label>
            <select 
              name="selectProveedor" 
              id="selectProveedor"
              class="form-select" 
              v-model="form.proveedor_id"
            >
              <option :value="null">--------------</option>
              <option v-for="i in cat.proveedores" :value="i.id">{{ i.nombre }}</option>
            </select>
          </div>

          <div class="col-sm-4">
            <label for="selectSucursal" class="form-label fw-bold">
              Sucursal: <span class="text-danger">*</span>
            </label>
            <select 
              name="selectSucursal" 
              id="selectSucursal"
              class="form-select"
              v-model="form.sucursal_id"
            >
              <option :value="null">--------------</option>
              <option v-for="i in cat.sucursales" :value="i.id">{{ i.nombre }}</option>
            </select>
          </div>

          <div class="col-sm-4">
            <label for="selectMoneda" class="form-label fw-bold">
              Moneda: <span class="text-danger">*</span>
            </label>
            <select 
              name="selectMoneda" 
              id="selectMoneda"
              class="form-select"
              v-model="form.moneda_id"
            >
              <option :value="null">--------------</option>
              <option v-for="i in cat.monedas" :value="i.id">{{ i.simbolo }} - {{ i.nombre }}</option>
            </select>
          </div>

          <div class="col-sm-4">
            <label for="selectMoneda" class="form-label fw-bold">
              Tipo Pago: <span class="text-danger">*</span>
            </label>
            <select 
              name="selectMoneda" 
              id="selectMoneda"
              class="form-select" 
              v-model="form.tipo_pago_id"
            >
              <option :value="null">--------------</option>
              <option v-for="i in cat.pagos" :value="i.id">{{ i.nombre }}</option>
            </select>
          </div>

          <div class="col-sm-4">
            <label for="selectMoneda" class="form-label fw-bold">
              Estado: <span class="text-danger">*</span>
            </label>
            <select 
              name="selectMoneda" 
              id="selectMoneda"
              class="form-select" 
              v-model="form.estado_orden_compra_id"
            >
              <option :value="null">--------------</option>
              <option v-for="i in cat.estados" :value="i.id">{{ i.nombre }}</option>
            </select>
          </div>

           <div class="col-sm-4">
            <label for="selectProveedor" class="form-label fw-bold">
              Referencia:
            </label>
            <input type="text" class="form-control" v-model="form.referencia">
          </div>

          <div class="col-sm-12">
            <label for="selectProveedor" class="form-label fw-bold">
              Observaciones:
            </label>
            <div class="form-control p-0">
              <div class="border-0">
                <QuillEditor class="h-150px" ref="editor"/>
              </div>
            </div>
          </div>

          <div class="col-sm-12 mt-3 text-end">
            <button 
              class="btn btn-default me-1" 
              type="button" 
              @click="$emit('regresar')"
            >
              <i class="fas fa-times"></i> Cancelar
            </button>
            <button 
              type="button" 
              class="btn btn-primary" 
              @click="guardar"
              :disabled="btnGuardar"
            >
              <span v-if="btnGuardar === false">
                <i class="fa fa-save me-1"></i> Guardar
              </span>

              <template v-else>
                <span class="spinner-grow spinner-grow-sm me-2" aria-hidden="true"></span>
                <span role="status">Guardando...</span>    
              </template>  
            </button>
          </div>
        </div>
      </form>
    </div>
  </div>
</template>

<script>
  export default {
    name: "FormOrdenCompra",
    emits: ["regresar","actualizar"],
    props: {
      compra: {
        type: Object,
        default: null
      },
      cat: {
        type: Object
      },
      modo: {
        type: Number,
        default: 1
      }
    },
    data: () => ({
      url: "compra/orden",
      btnGuardar: false,
      form: {},
      fbase: {},
      reg: "",
      _key: "id"
    }),
    created() {
      if (this.compra == null) {
        this.getCorrelativo()

        this.fbase = {
          proveedor_id: null,
          sucursal_id: null,
          moneda_id: null,
          tipo_pago_id: null,
          estado_orden_compra_id: null
        }

        this.form = {...this.form, ...this.fbase}
      } else {
        this.setDataForm(this.compra)
      }
    },
    methods: {
      async getCorrelativo() {
        this.inicio = true 

        await this.$http
        .get(`${this.$baseUrl}/${this.url}/get_correlativo`)
        .then(res => {

          this.inicio = false
          let corel = res.data.correlativo

          this.form.correlativo = corel.correlativo
          this.form.documento = corel.documento

        }).catch(e => {
          this.inicio = false
          console.log(e)
        })
      },
      guardar() {
        if (confirm("¿Está seguro de guardar?")) {
          this.btnGuardar = true

          this.$http
          .post(`${this.$baseUrl}/${this.url}/guardar/${this.reg}`, this.form)
          .then(result => {
            let res = result.data

            if (res.exito) {
              this.$toast.success(res.mensaje)
              this.$emit("actualizar", res.linea)
            } else {
              this.$toast.error(res.mensaje)
            }
            this.btnGuardar = false
          })
          .catch(e => {
            console.log(e)
            this.btnGuardar = false
          });
        }
      },
      setDataForm(obj) {
        this.reg = obj[this._key]
        
        for (let i in obj) {
          this.form[i] = obj[i]
        }
      }
    }
  }
</script>