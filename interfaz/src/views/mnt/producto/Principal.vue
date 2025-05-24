<template>
  <div class="d-flex align-items-center mb-3">
    <div>
      <ul class="breadcrumb">
        <li class="breadcrumb-item">
          <a href="#">MANTENIMIENTOS</a>
        </li>
        <li class="breadcrumb-item active">
          PRODUCTO
        </li>
      </ul>

      <h1 class="page-header mb-0 d-flex align-items-center">
        <button 
          v-if="actual == 2"
          class="btn btn-secondary me-1"
          @click="regresar"
        >
          <i class="fas fa-arrow-left"></i>
        </button>  
        <i class="fas fa-boxes-stacked me-1" v-if="actual == 1"></i> Producto
      </h1>
    </div>
    <div class="ms-auto">
      <a 
        href="#"
        v-if="actual == 1"
        class="btn btn-theme" 
        @click="verProducto(null)"
      >
        <i class="fa fa-plus-circle fa-fw me-1"></i> Nuevo
      </a>
    </div>
  </div>

  <div class="card" v-if="actual == 1">
    <div class="card-body p-0">
      <div class="card-header p-3" style="background-color: #ffff">
        <input type="text" class="form-control" placeholder="Buscar...">
      </div>
      <div class="table-responsive">
        <table class="table table-sm m-0">
          <thead class="table-dark">
            <tr>
              <th class="text-center">#</th>
              <th class="text-center" width="100">Imagen</th>
              <th>Código</th>
              <th>Producto</th>
              <th class="text-center">Tipo</th>
              <th>Categoría</th>
              <th>Marca</th>
              <th>Um</th>
              <th class="text-center">Estado</th>
            </tr>
          </thead>
          <tbody>
            <tr 
              v-for="(i, idx) in lista" 
              @click="verProducto(i)" 
              style="cursor: pointer;"
            >
              <th class="text-center">{{ idx + 1 }}</th>
              <td class="text-center">
                <Imagen
                  :img="i.imagen_key"
                  :width="50"
                ></Imagen>
              </td>
              <td>{{ i.codigo }}</td>
              <td>{{ i.nombre }}</td>
              <td class="text-center">{{ i.tipo }}</td>
              <td>
                <span
                  :class="'badge bg-'+i.etiqueta+' text-'+i.etiqueta+'-800 bg-opacity-25 px-2 pt-5px pb-5px rounded fs-12px d-inline-flex align-items-center'"
                >
                  <i :class="'fa fa-circle text-'+i.etiqueta+' fs-9px fa-fw me-5px'"></i> {{ i.nombre_categoria }}
                </span>
              </td>
              <td>{{ i.nombre_marca }}</td>
              <td>{{ i.nombre_um }}</td>
              <td class="text-center">
                <span 
                  v-if="i.activo == 1"
                  class="badge bg-success text-success-800 bg-opacity-20 px-2 pt-5px pb-5px rounded fs-12px d-inline-flex align-items-center"
                >
                  <i class="fa fa-circle text-success fs-9px fa-fw me-5px"></i> Activo
                </span>

                <span 
                  v-else
                  class="badge bg-red text-red-800 bg-opacity-20 px-2 pt-5px pb-5px rounded fs-12px d-inline-flex align-items-center"
                >
                  <i class="fa fa-circle text-red fs-9px fa-fw me-5px"></i> Inactivo
                </span>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
  
  <Form 
    v-if="actual == 2"
    :producto="producto"
    @actualizar="actLista"
  ></Form>

</template>

<script>
  import Form from "@/views/mnt/producto/Form.vue"
  import Imagen from "@/components/general/Imagen.vue"

  export default {
    name: "Producto",
    data: () => ({
      lista: [],
      bform: {},
      inicio: false,
      actual: 1,
      producto: null
    }),
    created() {
      this.buscar()
    },
    methods: {
      buscar() {
        this.inicio = true 

        this.$http
        .get(`${this.$baseUrl}/mnt/producto/buscar`, {params: this.bform})
        .then(res => {

          this.inicio = false
          this.lista = res.data.lista

        }).catch(e => {
          this.inicio = false
          console.log(e)
        })
      },
      actLista(obj) {
        if (this.producto === null) {
          this.lista.push(obj)
          this.producto = obj

        } else {
          for (let i in this.producto) {
            this.producto[i] = obj[i]
          }
        }
      },
      verProducto(obj) {
        this.actual = 2
        this.producto = obj
      },
      regresar() {
        this.actual = 1
        this.producto = null
      }
    },
    components: {
      Form,
      Imagen
    }
  }
</script>

<style>
  td {
    vertical-align: middle;
  }
</style>