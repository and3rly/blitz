<template>
  <div class="d-flex align-items-center mb-3">
    <div>
      <ul class="breadcrumb">
        <li class="breadcrumb-item">
          <a href="#">CONFIGURACIÓN</a>
        </li>
        <li class="breadcrumb-item active">
          PRODUCTO - SUCURSAL
        </li>
      </ul>

      <h3 class="mb-0 d-flex align-items-center">
        <i class="fas fa-list me-1"></i> Producto Sucursal
      </h3>
    </div>
    <div class="ms-auto">
    </div>
  </div>

  <div class="card">
    <div class="card-body p-0">
      <div class="table-responsive">
        <table class="table table-sm m-0">
          <thead class="table-dark">
            <tr>
              <th class="text-center">#</th>
              <th>Nombre</th>
              <th width="100"></th>
            </tr>
          </thead>
          <tbody>
            <tr v-if="inicio">
              <td colspan="100" class="text-center py-3">
                <div class="spinner-border text-dark"></div><br>
                Cargando...
              </td>
            </tr>
            <template v-else>
              <tr v-if="!cat">
                <td 
                  colspan="100" 
                  class="text-center py-3"
                >
                  No se encontraron registros.
                </td>
              </tr>
              <tr 
                v-for="(i, idx) in cat.sucursales" 
                v-else
              >
                <td class="text-center fw-bold">{{ idx + 1 }}</td>
                <td>{{ i.nombre }}</td>
                <td>
                  <button
                    class="btn btn-sm btn-lime"
                    title="Asignar productos"
                    @click=""
                  >
                    <i class="fas fa-arrow-right"></i>
                  </button>
                </td>
              </tr>
            </template>
          </tbody>
        </table>
      </div>
    </div>
  </div>  
</template>

<script>
  export default {
    name: "ProductoSucursal",
    data: () => ({
      inicio: false,
      cat: null,
      url: "producto/producto_sucursal"
    }),
    created() {
      this.getDatos()
    },
    methods: {
      getDatos() {
        this.inicio = true 

        this.$http
        .get(`${this.$baseUrl}/${this.url}/get_datos`)
        .then(res => {

          this.inicio = false
          this.cat = res.data.cat

        }).catch(e => {
          this.inicio = false
          console.log(e)
        })
      }
    }
  }
</script>