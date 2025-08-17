<template>
  <div class="d-flex align-items-center mb-3">
    <div>
      <ul class="breadcrumb">
        <li class="breadcrumb-item">
          <a href="#">TRANSACCIÓN</a>
        </li>
        <li class="breadcrumb-item active">
          ORDEN DE COMPRA
        </li>
      </ul>

      <h3 class="mb-0 d-flex align-items-center">
        <i class="fas fa-indent me-2"></i> Orden de Compra {{ compra != null ? ' - ' + compra.documento : ''}}
      </h3>
    </div>
    <div class="ms-auto" v-if="actual == 1">
      <button 
        class="btn btn-theme" 
        @click="nuevo"
      >
        <i class="fas fa-circle-plus"></i> Nueva
      </button>
    </div>
  </div>

  <div class="card" v-if="actual == 1">
    <div class="card-body">
      <div class="table-responsive">
        <table class="table table-sm m-0">
          <thead>
            <tr>
              <th class="text-center">#</th>
              <th>Documento</th>
              <th>Fecha</th>
              <th>Proveedor</th>
              <th>Sucursal</th>
              <th>Tipo Pago</th>
              <th>Estado</th>
              <th class="text-end">Total</th>
            </tr>
          </thead>
          <tbody>
            <template v-if="inicio">
              <tr>
                <td colspan="100" class="text-center py-3">
                  <div class="spinner-border text-dark"></div><br>
                  Cargando...
                </td>
              </tr>
            </template>
            <template v-else>
              <tr v-if="lista.length == 0 && !inicio">
                <td 
                  colspan="100" 
                  class="text-center py-3"
                >
                  No se encontraron registros.
                </td>
              </tr>
              <tr v-for="(i, idx) in lista" v-else style="cursor: pointer;">
                <td class="fw-bold text-center">{{ idx + 1 }}</td>
                <td>
                  <a 
                    href="javascript:;" 
                    class="text-decoration-none fw-bold"
                    @click="verCompra(i)"
                  >
                    {{ i.documento }}
                  </a>
                </td>
                <td>{{ formatoFecha(i.fecha, 2) }}</td>
                <td>{{ i.nombre_proveedor }}</td>
                <td>{{ i.nombre_sucursal }}</td>
                <td>{{ i.nombre_pago }}</td>
                <td>
                  <span
                    :class="'badge bg-'+i.color+' text-'+i.color+'-800 bg-opacity-25 px-2 pt-5px pb-5px rounded fs-12px d-inline-flex align-items-center'"
                  >
                    <i :class="'fa fa-circle text-'+i.color+' fs-9px fa-fw me-5px'"></i> {{ i.nombre_estado }}
                  </span>
                </td>
                <td class="text-end">
                  <span class="float-start text-muted me-1">{{ i.simbolo }}</span>
                  <span>{{ formatoNumero(i.total, '0,0.00') }}</span>
                </td>
              </tr>
            </template>
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <Detalle
    v-if="actual == 2"
    :compra="compra"
    :cat="catalogo"
    @regresar="regresar"
    @actualizar="actLista"
  ></Detalle>

  <Form
    v-if="actual == 3"
    :compra="compra"
    :cat="catalogo"
    @regresar="regresar"
    @actualizar="actLista"
  ></Form>
</template>

<script>
  import Form from "@/views/compra/Form.vue"
  import Detalle from "@/views/compra/Detalle.vue"
  import Helper from "@/mixins/Helper.js"

  export default {
    name: "OrdenCompra",
    mixins: [Helper],
    data: () => ({
      url: "compra/orden",
      actual: 1,
      inicio: false,
      lista: [],
      bform: {},
      catalogo: {},
      compra: null
    }),
    created() {
      this.buscar()
      this.getDatos()
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
      buscar() {
        this.inicio = true 

        this.$http
        .get(`${this.$baseUrl}/${this.url}/buscar`, {params: this.bform})
        .then(res => {

          this.inicio = false
          this.lista = res.data.lista

        }).catch(e => {
          this.inicio = false
          console.log(e)
        })
      },
      verCompra(o) {
        this.actual = 2
        this.compra = o
      },
      nuevo() {
        this.actual = 3
        this.compra = null
      },
      regresar() {
        this.actual = 1
        this.compra = null
      },
      actLista(obj) {
        if (this.compra === null) {
          this.lista.unshift(obj)
          this.compra = obj
        } else {
          for (let i in this.compra) {
            this.compra[i] = obj[i]
          }
        }
      },
    },
    components: {
      Form,
      Detalle
    }
  }
</script>