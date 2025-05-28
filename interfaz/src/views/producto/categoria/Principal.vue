<template>
  <div class="d-flex align-items-center mb-3">
    <div>
      <ul class="breadcrumb">
        <li class="breadcrumb-item"><a href="#">MANTENIMIENTOS</a></li>
        <li class="breadcrumb-item active">CATEGORIA</li>
      </ul>
      <h3><i class="fas fa-list"></i> Categoría</h3>
    </div>
  </div>

  <div class="row">
    <div class="col-sm-5">
      <Card>
        <CardHeader>
          Datos Generales
        </CardHeader>
        <CardBody>
          <form @submit.prevent="guardar">
            <div class="mb-2 row">
              <label 
                for="inptuNombre" 
                class="col-sm-3 col-form-label fw-bold"
              >
                Nombre: <span class="text-danger">*</span>
              </label>
              <div class="col-sm-9">
                <input 
                  type="text" 
                  class="form-control" 
                  id="inptuNombre"
                  v-model="form.nombre"
                >
              </div>
            </div>

            <div class="row" v-if="reg !== ''">
              <div class="col-md-3 offset-md-3">
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
            </div> 

            <div class="col-sm-12 text-end mt-2">
              <button 
                v-if="reg !== ''"
                type="button" 
                class="btn btn-secondary me-2" 
                @click="limpiar"
              >
                Cancelar / Nuevo
              </button>

              <button 
                type="submit" 
                class="btn btn-primary"
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
          </form>
        </CardBody>
      </Card>
    </div>

    <div class="col-sm-7">
      <Card>
        <CardHeader class="p-2">
          <input 
            type="search" 
            class="form-control"
            placeholder="Buscar..."
            v-model="termino" 
          >
        </CardHeader>
        <CardBody>
          <div class="table-responsive">
            <table class="table table-sm table-hover m-0">
              <thead>
                <tr>
                  <th>#</th>
                  <th>Nombre</th>
                  <th>Estado</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="(i, idx) in filtrada" @click="setDataForm(i)" style="cursor: pointer;">
                  <td>{{ idx + 1 }}</td>
                  <td>{{ i.nombre }}</td>
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
    </div>
  </div>
</template>

<script>
  import Blitz from "@/mixins/Blitz.js"

  export default {
    name: "Categoria",
    mixins: [Blitz],
    data: () => ({

    }),
    created() {
      this.url = "producto/categoria"
    }
  }
</script>