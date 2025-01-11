<template>
  <div class="d-flex align-items-center mb-3">
    <div>
      <ul class="breadcrumb">
        <li class="breadcrumb-item">
          <a href="#">MANTENIMIENTOS</a>
        </li>
        <li class="breadcrumb-item active">
          PROVEEDOR
        </li>
      </ul>

      <h1 class="page-header mb-0 d-flex align-items-center"> 
        <i class="fas fa-user me-1"></i>Proveedor
      </h1>
    </div>
    <div class="ms-auto">
      <a 
        href="#"
        class="btn btn-theme"
        @click="verProveedor(null)"
      >
        <i class="fa fa-plus-circle fa-fw me-1"></i> Nuevo
      </a>
    </div>
  </div>
  
  <div class="card">
    <div class="card-header p-3" style="background-color: #ffff">
      <input type="text" class="form-control" placeholder="Buscar...">
    </div>

    <div class="card-body p-0">
      <div class="table-responsive">
        <table class="table table-sm m-0">
          <thead class="table-light">
            <tr>
              <th class="text-center">#</th>
              <th>Nombre</th>
              <th>Identificación</th>
              <th>Teléfono</th>
              <th>Dirección</th>
              <th>Departamento</th>
              <th>Municipio</th>
              <th class="text-center">Estado</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(i, idx) in lista" :key="idx">
              <th class="text-center">{{ idx + 1 }}</th>
              <th>
                <a href="javascript:;" class="text-decoration-none" @click="verProveedor(i)">
                  {{ i.nombre }}
                </a>
              </th>
              <td>{{ i.identificacion }}</td>
              <td>{{ i.telefono }}</td>
              <td>{{ i.direccion }}</td>
              <td>{{ i.nombre_departamento }}</td>
              <td>{{ i.nombre_municipio }}</td>
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

  <div 
    class="modal fade" 
    id="mdlForm" 
    data-bs-backdrop="static" 
    data-bs-keyboard="false"
    tabindex="-1"
    aria-labelledby="staticBackdropLabel" 
    aria-hidden="true"
  >
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="staticBackdropLabel">
            <i class="fas fa-user"></i> {{ proveedor != null ? "Proveedor: " + proveedor.nombre: "Nuevo proveedor" }}
          </h1>
          <button 
            type="button" 
            class="btn-close" 
            data-bs-dismiss="modal" 
            aria-label="Close" 
            @click="cerrarModal"
          ></button>
        </div>
        <div class="modal-body">
          <Form 
            v-if="verForm" 
            :proveedor="proveedor"
            @limpiar="proveedor = null"
            @actualizar="actLista"
          />
        </div>
      </div>
    </div>
  </div>
</template>

<script>
  import Form from "@/views/mnt/proveedor/Form.vue"

  export default {
    data: () => ({
      proveedor: null,
      inicio: false,
      verForm: false,
      lista: []
    }),
    created() {
      this.buscar()
    },
    methods: {
      verProveedor(obj) {
        this.verForm = true
        this.proveedor = obj

        this.mdlForm = this.setModal("mdlForm")
        this.mdlForm.show()
      },
      cerrarModal() {
        this.verForm = false
        this.mdlForm.hide()
      },
      buscar() {
        this.inicio = true

        this.$http
        .get(`${this.$baseUrl}/mnt/proveedor/buscar`, {params: this.bform})
        .then(res => {

          this.inicio = false
          this.lista = res.data.lista

        }).catch(e => {
          this.inicio = false
          console.log(e)
        })
      },
      actLista(obj) {
        if (this.proveedor === null) {
          this.lista.unshift(obj)
        } else {
          for (let i in this.proveedor) {
            this.proveedor[i] = obj[i]
          }
        }

        this.proveedor = null
        this.cerrarModal()
      }
    },
    components: {
      Form
    }
  }
</script>