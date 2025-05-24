<template>
  <div class="d-flex align-items-center mb-3">
    <h3 class="mb-0">
      <i class="fa fa-store me-1"></i>Sucursal
    </h3>
    <div class="ms-auto">
      <button class="btn btn-theme" @click="verSucursal(null)">
        <i class="fa fa-plus-circle fa-fw me-1"></i> Nuevo
      </button>
    </div>
  </div>

  <Card class="mt-3">
    <CardBody>
      <form @submit.prevent="buscar">
        <div class="input-group mt-3">
          <input 
            type="text" 
            class="form-control ps-35px"
            placeholder="Buscar..." 
            v-model="bform.termino"
            style="border-radius: 4px;" 
          />
          <div class="input-group-text position-absolute top-0 bottom-0 bg-none border-0" style="z-index: 1020;">
            <i class="fa fa-search opacity-5"></i>
          </div>
        </div>
      </form>

      <div class="table-responsive mt-4">
        <table class="table table-sm table-hover m-0">
          <thead>
            <tr>
              <th class="text-center">#</th>
              <th>Nombre</th>
              <th>Razón social</th>
              <th>Dirección</th>
              <th>Teléfono</th>
              <th>Departamento</th>
              <th>Municipio</th>
              <th>Estado</th>
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
              @click="verSucursal(i)"
              v-else
            >
              <td class="text-center">{{ idx + 1 }}</td>
              <td>{{ i.nombre }}</td>
              <td>{{ i.razon_social }}</td>
              <td>{{ i.direccion }}</td>
              <td>{{ i.telefono }}</td>
              <td>{{ i.nombre_departamento }}</td>
              <td>{{ i.nombre_municipio }}</td>
              <td>
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
    </CardBody>
  </Card>


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
          <h1 class="modal-title fs-5" id="staticBackdropLabel">
            <i class="fa fa-store mr-1"></i> Sucursal
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
            v-if="verForm === true" 
            :sucursal="sucursal"
            @limpiar="sucursal = null"
            @actualizar="actLista"
          ></Form>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
  import Form from "@/views/mnt/sucursal/Form.vue"

  export default {
    name: "CuerpoSucursal",
    data: () => ({
      inicio: false,
      verForm: false,
      actual: 1,
      bform: {},
      lista: [],
      modal: null,
      idx: null,
      termino: "",
      sucursal: null
    }),
    created() {
      this.buscar()
    },
    methods: {
      buscar() {
        this.inicio = true

        this.$http
        .get(`${this.$baseUrl}/mnt/sucursal/buscar`, {params: this.bform})
        .then(res => {

          this.inicio = false
          this.lista = res.data.lista

        }).catch(e => {
          this.inicio = false
          console.log(e)
        })
      },
      verSucursal(obj) {
        this.verForm = true
        this.modal = this.setModal("mdlSucursal")
        this.modal.show()

        this.sucursal = obj
      },
      cerrarModal() {
        this.verForm = false
        this.modal.hide()
      },
      actLista(obj) {
        if (this.sucursal === null) {
          this.lista.push(obj)
          this.sucursal = obj

        } else {
          for (let i in this.sucursal) {
            this.sucursal[i] = obj[i]
          }
        }
      }
    },
    cmoputed: {
     
    },
    components: {
      Form
    }
  }
</script>