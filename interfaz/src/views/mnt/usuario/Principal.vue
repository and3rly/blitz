<template>
  <div class="d-flex align-items-center mb-3">
    <div>
      <ul class="breadcrumb">
        <li class="breadcrumb-item">
          <a href="#">MANTENIMIENTOS</a>
        </li>
        <li class="breadcrumb-item active">
          USUARIO
        </li>
      </ul>

      <h1 class="page-header mb-0 d-flex align-items-center"> 
        <i class="fas fa-user me-1"></i> Usuario
      </h1>
    </div>
    <div class="ms-auto">
      <a 
        href="#"
        class="btn btn-theme"
        @click="verUsuario(null)"
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
        <table class="table table-sm table-drop m-0">
          <thead class="table-light">
            <tr>
              <th class="text-center">#</th>
              <th>Nombre</th>
              <th>Usuario</th>
              <th>Rol</th>
              <th class="text-center">Fecha</th>
              <th class="text-center">Estado</th>
              <th></th>
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
            <template v-if="lista.length == 0 && !inicio">
              <tr>
                <td 
                  colspan="100" 
                  class="text-center py-3"
                >
                  No se encontraron registros.
                </td>
              </tr>
            </template>
            <tr 
              v-for="(i, idx) in lista"
              style="cursor: pointer;" 
              :key="idx"
              v-else
            >
              <th class="text-center">{{ idx + 1 }}</th>
              <th>
                <a href="javascript:;" class="text-decoration-none" @click="verUsuario(i)">
                  {{ i.nombre }} {{ i.apellido }}
                </a>
              </th>
              <td>{{ i.usuario }}</td>
              <td>{{ i.nombre_rol }}</td>
              <td class="text-center">{{ formatoFecha(i.fecha, 2) }}</td>
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
              <td class="text-center">
                <div class="dropdown">
                  <a
                    href="javascript:;"
                    id="dropdownMenu" 
                    data-bs-toggle="dropdown" 
                    data-bs-display="static"
                    data-boundary="viewport" 
                    aria-expanded="false"
                  >
                    <i class="fa-solid fa-ellipsis"></i>
                  </a>
                  <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenu">
                    <li>
                      <a 
                        class="dropdown-item" 
                        href="javascript:;"
                        @click="setSucursal(i)"
                      >
                        <i class="fas fa-store me-2"></i>Asignar sucursal
                      </a>
                    </li>
                  </ul>
                </div>
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
            <i class="fas fa-user"></i> {{ usuario != null ? usuario.nombre +" "+ usuario.apellido: "Nuevo usuario" }}
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
            :usuario="usuario"
            @limpiar="usuario = null"
            @actualizar="actLista"
          />
        </div>
      </div>
    </div>
  </div>

  <div 
    class="modal fade" 
    id="mdlSucursal" 
    data-bs-backdrop="static" 
    data-bs-keyboard="false"
    tabindex="-1"
    aria-labelledby="staticBackdropLabel" 
    aria-hidden="true"
  >
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title fs-5" id="staticBackdropLabel" style="font-weight: normal;">
            <i class="fas fa-project-diagram"></i> Asignar de sucursales al usuario 
            <span 
              v-if="usuario !== null" 
              class="fw-bold"
            >
              {{ usuario.nombre }} {{ usuario.apellido }}
            </span>
          </h5>
          <button 
            type="button" 
            class="btn-close" 
            data-bs-dismiss="modal" 
            aria-label="Close" 
          ></button>
        </div>
        <div class="modal-body">
          <UsuarioSucursal></UsuarioSucursal>
        </div>
      </div>
    </div>
  </div>

</template>

<script>
  import Helper from "@/mixins/Helper.js"
  import Form from "@/views/mnt/usuario/Form.vue"
  import UsuarioSucursal from "@/views/mnt/usuario/UsuarioSucursal.vue"

  export default {
    mixins: [Helper],
    data: () => ({
      inicio:false,
      verForm: false,
      lista: [],
      usuario: null
    }),
    created() {
      this.buscar()
    },
    methods: {
      verUsuario(obj) {
        this.verForm = true
        this.usuario = obj

        this.mdlForm = this.setModal("mdlForm")
        this.mdlForm.show()
      },
      cerrarModal() {
        this.verForm = false
        this.mdlForm.hide()
      },
      setSucursal(obj) {
        this.usuario = obj
        this.mdlForm = this.setModal("mdlSucursal")
        this.mdlForm.show()
      },
      buscar() {
        this.inicio = true

        this.$http
        .get(`${this.$baseUrl}/usuario/buscar`, {params: this.bform})
        .then(res => {

          this.inicio = false
          this.lista = res.data.lista

        }).catch(e => {
          this.inicio = false
          console.log(e)
        })
      },
      actLista(obj) {
        if (this.usuario === null) {
          this.lista.unshift(obj)
        } else {
          for (let i in this.usuario) {
            this.usuario[i] = obj[i]
          }
        }

        this.usuario = null
        this.cerrarModal()
      }
    },
    components: {
      Form,
      UsuarioSucursal
    }
  }
</script>

<style>
  @media (max-width: 767px) {
    .table-responsive .dropdown-menu {
        position: static !important;
    }
  }

  @media (min-width: 768px) {
    .table-responsive {
        overflow: visible;
    }
  }
</style>