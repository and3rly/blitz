<template>
  <template v-if="verForm == 1"> 
    <div class="mb-3 d-md-flex fw-bold">
      <div class="mt-md-0 mt-2">
        <a 
          href="#" 
          class="text-decoration-none text-body" 
          @click="$emit('regresar')"
        >
          <i class="far fa-arrow-alt-circle-left fa-fw me-1 text-body text-opacity-50"></i>
          Regresar
        </a>
      </div>

      <div class="ms-md-4 mt-md-0 mt-2">
        <a href="#" class="text-decoration-none text-body">
          <i class="bi bi-printer fa-fw me-1 text-body text-opacity-50"></i>
          Imprimir
        </a>
      </div>

      <div class="ms-md-4 mt-md-0 mt-2">
        <a href="#" class="text-decoration-none text-body">
          <i class="bi bi-boxes fa-fw me-1 text-body text-opacity-50"></i>
          Recibir
        </a>
      </div>

      <div class="ms-md-4 mt-md-0 mt-2">
        <a href="#" class="text-decoration-none text-body" @click="guardar">
          <i class="fas fa-save fa-fw me-1 text-body text-opacity-50"></i>
          Guardar
        </a>
      </div>
    </div>

    <div class="row">
      <div :class="!verEnc ? 'col-sm-12 pe-1' : 'col-sm-8'">
        <div class="card">
          <div class="card-header d-flex align-items-center bg-inverse bg-opacity-10 fw-400"> 
            <span 
              v-if="verEnc == false"
              title="Contraer y ver información de la orden de compra" 
              class="d-flex align-items-center justify-content-center rounded-circle border bg-white me-1 p-1 hover-bg"
              style="cursor: pointer;"
              @click="verEnc = !verEnc"
            >
              <i class="fas fa-angle-double-left"></i>
            </span>
            <span class="fw-bold">Productos ({{ form.detalle.length }}) </span>
            <a 
              href="#" 
              class="ms-auto text-decoration-none text-body text-opacity-50"
              @click="verProductos"
            >
              <i class="fas fa-circle-plus fa-lg me-1"></i> Agregar
            </a>
          </div>
          <div class="card-body text-body m-0">
            <div class="table-responsive">
              <table class="table table-sm m-0 align-middle">
                <thead>
                  <tr>
                    <th>#</th>
                    <th width="100">Imagen</th>
                    <th>Nombre</th>
                    <th class="text-center" width="100">Presentación</th>
                    <th class="text-center" width="100">Precio</th>
                    <th class="text-center" width="100">Cantidad</th>
                    <th class="text-end">Total</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="(i, idx) in form.detalle">
                    <td class="fw-bold">
                      {{ idx + 1 }}
                    </td>
                    <td>
                      <div class="h-60 w-50px d-flex align-items-center justify-content-center position-relative bg-white p-1">
                        <Imagen
                          :img="i.imagen_key"
                          :clase="'mw-100 mh-100'"
                        ></Imagen>
                      </div>
                    </td>
                    <td>
                      <div class="fw-bold">{{ i.nombre_producto }}</div>
                      <small class="text-muted">SKU: IP13PROMAX-512</small>
                    </td>
                    <td>
                      <select 
                        name="selectPresentacion" 
                        id="selectPresentacion" 
                        class="form-select" 
                        v-model="i.producto_presentacion_id"
                      >
                        <option :value="null">--------</option>
                        <option 
                          v-for="j in listarPresentaciones(i)" 
                          :value="j.id"
                        >
                          {{ j.nombre }}
                        </option>
                      </select>
                    </td>
                    <td>
                      <input 
                        type="number" 
                        class="form-control text-end"
                        step="0.01" 
                        min="0" 
                        v-model="i.precio"
                        @input="calculaTotal(i)"
                      >
                    </td>
                    <td>
                      <input 
                        type="number"
                        class="form-control text-center"
                        min="1" 
                        v-model="i.cantidad"
                        @input="calculaTotal(i)"
                      >
                    </td>
                    <td class="text-end">
                      {{ compra.simbolo }} {{ formatoNumero(i.total, '0,0.00') }}
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>

            <div class="text-end mb-0 mt-2">
              <button 
                type="button"
                class="btn btn-primary"
                @click="guardar"
              >
                <i class="fas fa-save me-1"></i>Guardar
              </button>
            </div>
          </div>
        </div>
      </div>
      
      <div class="col-sm-4" :style="!verEnc ? 'display: none;':'display:block;' ">
        <div class="card">
          <div class="card-header d-flex align-items-center bg-inverse bg-opacity-10 fw-400">
            <span 
              title="Ocultar encabezado orde de compra" 
              class="d-flex align-items-center justify-content-center rounded-circle border bg-white me-1 p-1 hover-bg"
              style="cursor: pointer;"
              @click="verEnc = !verEnc"
            >
              <i class="fas fa-angle-double-right"></i>
            </span>
            <span class="fw-bold">Datos Generales</span> 
            <a href="#" class="ms-auto text-decoration-none text-body text-opacity-50" @click="verCompra">Editar</a>
          </div>
          <div class="card-body">
            <table class="table table-sm table-borderless mb-0">
              <tbody>
                <tr>
                  <th class=""> <i class="far fa-file-alt me-1"></i>Documento:</th>
                  <td> <a href="#" class="text-decoration-none fw-bold">{{ compra.documento }}</a></td>
                </tr>
                <tr>
                  <th class=""> <i class="far fa-calendar-check me-1"></i>Fecha:</th>
                  <td>{{ compra.fecha }}</td>
                </tr>
                <tr>
                  <th><i class="far fa-user me-1"></i>Proveedor:</th>
                  <td>{{ compra.nombre_proveedor }}</td>
                </tr>
                <tr>
                  <th><i class="far fa-building me-1"></i>Sucursal:</th>
                  <td>{{ compra.nombre_sucursal }}</td>
                </tr>
                <tr>
                  <th><i class="far fa-file-alt me-1"></i>Tipo de Pago:</th>
                  <td>{{ compra.nombre_pago }}</td>
                </tr>
                <tr>
                  <th><i class="fas fa-tag me-1"></i>Estado:</th>
                   <span
                    :class="'badge bg-'+compra.color+' text-'+compra.color+'-800 bg-opacity-25 px-2 pt-3px pb-5px rounded fs-12px d-inline-flex align-items-center'"
                  >
                    <i :class="'fa fa-circle text-'+compra.color+' fs-9px fa-fw me-5px'"></i> {{ compra.nombre_estado }}
                  </span>
                </tr>
                <tr>
                  <th><i class="far fa-circle me-1"></i>Total:</th>
                  <td>{{ compra.simbolo }} {{ formatoNumero(compra.total, '0,0.00') }}</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </template>

  <Form
    v-else
    :compra="compra"
    :cat="cat"
    @regresar="verForm = 1"
    @actualizar="actualizarEnc"
  ></Form>

  <div 
    class="modal fade" 
    id="mdlProductos" 
    data-bs-backdrop="static" 
    data-bs-keyboard="false"
    tabindex="-1"
    aria-labelledby="staticBackdropLabel" 
    aria-hidden="true"
  >
    <div class="modal-dialog modal-xl">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="staticBackdropLabel">
            Productos
          </h1>
          <button 
            type="button" 
            class="btn-close" 
            data-bs-dismiss="modal" 
            aria-label="Close"
          ></button>
        </div>
        <div class="modal-body">
          <div class="table-responsive">
            <table class="table table-sm">
              <thead>
                <tr>
                  <th class="text-center">#</th>
                  <th>Nombre</th>
                  <th>Marca</th>
                  <th>Categoría</th>
                  <th width="100" class="text-center">Cantidad</th>
                  <!--th width="100" class="text-center">Precio</th-->
                  <th></th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="(i, idx) in catalogo.productos">
                  <td class="text-center">{{ idx + 1 }}</td>
                  <td>{{ i.nombre_producto }}</td>
                  <td>{{ i.nombre_marca }}</td>
                  <td>
                    <span
                      :class="'badge bg-'+i.etiqueta+' text-'+i.etiqueta+'-800 bg-opacity-25 px-2 pt-5px pb-5px rounded fs-12px d-inline-flex align-items-center'"
                    >
                      <i :class="'fa fa-circle text-'+i.etiqueta+' fs-9px fa-fw me-5px'"></i> {{ i.nombre_categoria }}
                    </span>
                  </td>
                  <td class="text-center">
                    <input type="number" class="form-control text-center" v-model="i.cantidad">
                  </td>
                  <!--td class="text-center">
                    <input 
                      type="number" 
                      class="form-control text-center"
                      step="0.01"
                      min="0" 
                      value="0" 
                      v-model="i.precio"
                    >
                  </td-->
                  <td class="text-center">
                    <button
                      type="button"
                      class="btn btn-sm btn-lime"
                      @click="agregarProducto(i)"
                    >
                      <i class="fas fa-plus me-1"></i>Agregar
                    </button>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
  import Form from "@/views/compra/Form.vue"
  import Imagen from "@/components/general/Imagen.vue"
  import Helper from "@/mixins/Helper.js"

  export default {
    name:"DetalleOc",
    mixins: [Helper],
    emits: ["regresar","actualizar"],
    props: {
      compra: {
        type: Object,
        required: true
      },
      cat:{
        type: Object
      }
    },
    data: () => ({
      url: "compra/detalle",
      verForm: 1,
      verEnc: false,
      inicio: false,
      btnGuardar: false,
      catalogo: {},
      productos: [],
      form: {
        detalle: []
      },
    }),
    created() {
      this.getDatos()
      this.getDetalle()
    },
    methods: {
      getDatos() {
        this.inicio = true 

        this.$http
        .get(`${this.$baseUrl}/${this.url}/get_datos`)
        .then(res => {

          this.inicio = false
          this.catalogo = res.data.cat

        }).catch(e => {
          this.inicio = false
          console.log(e)
        })
      },
      getDetalle() {
        this.inicio = true 

        this.$http
        .get(`${this.$baseUrl}/${this.url}/buscar`, {params: { orden_compra_id: this.compra.id }})
        .then(res => {

          this.inicio = false
          this.form.detalle = res.data.lista

        }).catch(e => {
          this.inicio = false
          console.log(e)
        })
      },
      guardar() {
        this.btnGuardar = true
        this.form.orden_compra_id = this.compra.id

        let errores = []

        this.form.detalle.forEach((o, idx) => {
          let precio = o.precio
          let cantidad = o.cantidad

          o.errPrecio   = (precio === '' || precio == null || parseFloat(precio) == 0)
          o.errCantidad = (cantidad === '' || cantidad == null || parseInt(cantidad) == 0)

          let partes = [];
          if (o.errCantidad) partes.push('Cantidad inválida');
          if (o.errPrecio)   partes.push('Precio inválido');

          if (partes.length) {
            errores.push({
              idx,
              detalle: partes.join(' y ')
            });
          }
        })

        if (errores.length) {
          let msj = 'Revisar las siguientes filas:\n' + errores.map(e => `Fila #${e.idx + 1}: ${e.detalle}`).join('\n');
          this.$toast?.error?.(msj, { timeout: 7000 });

          return
        }

        this.$http
        .post(`${this.$baseUrl}/${this.url}/guardar`, this.form)
        .then(result => {
          let res = result.data

          if (res.exito) {
            this.$toast.success(res.mensaje)
            this.form.detalle = res.detalle

            this.$emit("actualizar", res.compra)
          } else {
            this.$toast.error(res.mensaje)
          }
          this.btnGuardar = false
        })
        .catch(e => {
          console.log(e)
          this.btnGuardar = false
        });        
      },
      agregarProducto(o) {
        let reg = this.form.detalle.find(e => e.producto_sucursal_id == o.id)

        if (reg) {
          reg.cantidad = parseFloat(reg.cantidad) + parseFloat(o.cantidad)
          return
        }

        let producto = {
          id: null,
          nombre_producto: o.nombre_producto,
          imagen_key: o.imagen_key,
          producto_sucursal_id: o.id,
          producto_id: o.producto_id,
          cantidad: o.cantidad,
          precio: 0,
          cantidad_recibida: 0,
          anulado: 0,
          producto_presentacion_id: null,
          total: 0
        }

        this.form.detalle.push(producto)
      },
      calculaTotal(o) {
        o.total = (parseFloat(o.cantidad) * parseFloat(o.precio)).toFixed(2)
      },
      verCompra() {
        this.verForm = 2
      },
      verProductos() {
        this.$abrirModal("mdlProductos")
        //this.getDatos()
      },
      listarPresentaciones(o) {
        if (this.catalogo.presentaciones) {
          return this.catalogo.presentaciones.filter(e => {
            return e.producto_id == o.producto_id
          })
        }

        return []
      },
      actualizarEnc(o) {
        this.$emit("actualizar", o)
      }
    },
    computed: {
    },
    components: {
      Form,
      Imagen
    }
  }
</script>