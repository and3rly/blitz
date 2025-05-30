<template>
  <div class="card shadow-sm border-0 rounded-2">
    <div class="card-body">
      <div class="row align-items-center mb-2">
        <div class="col-auto text-start">
          <button class="btn btn-secondary" @click="$emit('regresar')">
            <i class="fas fa-arrow-left me-1"></i>
          </button>
        </div>
        <div class="col text-center">
          <h4 class="mt-3 mb-0">Sucursal - {{ sucursal.nombre }}</h4>
        </div>
      </div>

      <p class="text-muted text-center mb-4">
        Esta sección del sistema está destinada a asignar productos a las diferentes sucursales.
        Asegúrate de verificar cada selección antes de guardar los cambios.
      </p>
      <div class="row align-items start g-3">
        <div class="col">
          <div class="card">
            <div class="card-header fw-bold">
              Productos disponibles
            </div>
            <div class="card-body p-0">
              <ul class="list-group list-group-flush">
                <li 
                  class="list-group-item" 
                  v-if="disponibles.length > 0 && !inicio"
                  v-for="i in disponibles"
                >
                  <input 
                    type="checkbox" 
                    :value="i" 
                    v-model="producto"
                  >
                  {{ i.nombre }} <br>
                  <span
                    :class="'badge bg-'+i.etiqueta+' text-'+i.etiqueta+'-800 bg-opacity-25 px-2 pt-5px pb-5px rounded fs-12px d-inline-flex align-items-center mt-1'"
                  >
                    <i :class="'fa fa-circle text-'+i.etiqueta+' fs-9px fa-fw me-5px'"></i> {{ i.nombre_categoria }}
                  </span>
                </li>
                <li 
                  class="list-group-item text-center"  
                  v-else
                >
                  Sin productos disponibles.
                </li>
              </ul>
            </div>
          </div>
        </div>

        <div class="col-auto d-flex flex-column justify-content-center py-5">
          <button 
            class="btn btn-lime mb-2" 
            title="Asignar productos" 
            @click="asignar" 
            :disabled="pasignado.length > 0"
          >
            <i class="fas fa-arrow-right fs-7"></i>
          </button>
          <button 
            class="btn btn-secondary" 
            title="Quitar productos" 
            @click="quitar" 
            :disabled="producto.length > 0"
          >
            <i class="fas fa-arrow-left fs-7"></i>
          </button>
        </div>

        <div class="col">
          <div class="card">
            <div class="card-header fw-bold">
              Productos asignados
            </div>
            <div class="card-body p-0">
              <ul class="list-group list-group-flush">
                <li 
                  class="list-group-item"
                  v-if="lista.length > 0 && !inicio" 
                  v-for="i in lista" 
                >
                  <input 
                    type="checkbox" 
                    :value="i" 
                    v-model="pasignado"
                  >
                  {{ i.nombre_producto }} <br>
                  <span
                    :class="'badge bg-'+i.etiqueta+' text-'+i.etiqueta+'-800 bg-opacity-25 px-2 pt-5px pb-5px rounded fs-12px d-inline-flex align-items-center mt-1'"
                  >
                    <i :class="'fa fa-circle text-'+i.etiqueta+' fs-9px fa-fw me-5px'"></i> {{ i.nombre_categoria }}
                  </span>
                </li>
                <li 
                  class="list-group-item text-center"  
                  v-else
                >
                  No se encontraron registros.
                </li>
              </ul>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
  export default {
    name: "Productos",
    props: {
      productos: {
        type: Array,
        default: []
      },
      sucursal: {
        type: Object,
        default: null
      }
    },
    data: () => ({
      url: "producto/producto_sucursal",
      inicio: false,
      form: {},
      lista: [],
      producto: [],
      pasignado: [],
      reg: ""
    }),
    created() {
      this.getProductoSucursal()
    },
    methods: {
      getProductoSucursal() {
        this.inicio = true 
        let bform = {
          sucursal: this.sucursal.id
        }

        this.$http
        .get(`${this.$baseUrl}/${this.url}/buscar`, {params: bform})
        .then(res => {

          this.inicio = false
          this.lista = res.data.lista

        }).catch(e => {
          this.inicio = false
          console.log(e)
        })
      },
      guardar() {
        if (confirm("¿Está seguro de guardar?")) {
          this.btnGuardar = true

          this.form.sucursal = this.sucursal.id 

          this.$http
          .post(`${this.$baseUrl}/${this.url}/guardar/${this.reg}`, this.form)
          .then(result => {
            let res = result.data

            if (res.exito) {
              this.$toast.success(res.mensaje)
              this.lista = res.lista

              this.form      = {}
              this.producto  = []
              this.pasignado = []
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
      asignar() {
        this.form.producto = this.producto
        this.guardar()
      },
      quitar() {
        this.form.producto = this.pasignado
        this.guardar()
      }
    },
    computed: {
      disponibles() {
        let asignados = this.lista.map(p => p.producto_id)
        return this.productos.filter(p => !asignados.includes(p.id))
      }
    }
  }
</script>