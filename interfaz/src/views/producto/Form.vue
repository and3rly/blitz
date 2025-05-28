<template>
  <div class="row">
    <div class="col-sm-3">
      <div class="card">
        <div class="card-header fw-bold">
          Imagen
        </div>
        <div class="card-body py-4">
          <div class="col-sm-12 text-center">
            <Imagen
              :img="imgActual"
            ></Imagen>
          </div>

          <label 
            for="file-upload" 
            class="btn btn-light d-flex align-items-center justify-content-center d-grid gap-2 mt-4"
          > 
            <template v-if="reg == '' || this.producto == null">
              <i class="fas fa-plus"></i> Subir imagen
            </template>

            <template v-else>
              <i class="fas fa-plus"></i> Cambiar imagen
            </template>

          </label>
          <input 
            id="file-upload" 
            class="form-control" 
            type="file"
            @change="subirImagen"
          />            
        </div>
      </div>
    </div>

    <div class="col-sm-9">
      <div class="card">
        <div class="card-header fw-bold">
          Datos generales
        </div>
        <div class="card-body">
          <form @submit.prevent="guardar">
            <div class="row g-2">
              <div class="col-sm-6">
                <label 
                  for="inputCodigo" 
                  class="form-label fw-bold mb-1"
                >
                  Código:
                </label>
                <input 
                  type="text" 
                  class="form-control" 
                  id="inputCodigo"
                  v-model="form.codigo"
                />
              </div>

              <div class="col-sm-6">
                <label 
                  for="inputNombre" 
                  class="form-label fw-bold mb-1"
                >
                  Nombre: <span class="text-danger">*</span>
                </label>
                <input 
                  type="text" 
                  class="form-control" 
                  id="inputNombre"
                  v-model="form.nombre"
                  required
                />
              </div>

              <div class="col-sm-6">
                <label 
                  for="selectTipo" 
                  class="form-label fw-bold mb-1"
                >
                  Tipo: <span class="text-danger">*</span>
                </label>
                <select
                  class="form-select"
                  name="selectTipo" 
                  id="selectTipo"
                  v-model="form.tipo"
                >
                  <option :value="null">------------</option>
                  <option v-for="i in cat.tipos" :value="i">{{ i }}</option>
                </select>
              </div>

              <div class="col-sm-6">
                <label
                  class="form-label fw-bold mb-1"
                >
                  Unidad de medida: <span class="text-danger">*</span>
                </label>
                <vue-select
                  id="selectUm"
                  :options="ums"
                  :reduce="r => r.value"
                  placeholder="------------"
                  v-model="form.unidad_medida_id"
                >  
                </vue-select>
              </div>

              <div class="col-sm-6">
                <label
                  class="form-label fw-bold mb-1"
                >
                  Marca: <span class="text-danger">*</span>
                </label>
                <vue-select
                  id="selectMarca"
                  :options="marcas"
                  :reduce="r => r.value"
                  placeholder="------------"
                  v-model="form.marca_id"
                >  
                </vue-select>
              </div>


              <div class="col-sm-6">
                <label
                  class="form-label fw-bold mb-1"
                >
                  Categoría: <span class="text-danger">*</span>
                </label>
                <vue-select
                  id="selectCat"
                  :options="categorias"
                  :reduce="r => r.value"
                  placeholder="------------"
                  v-model="form.categoria_id"
                >  
                </vue-select>
              </div>

              <div class="col-sm-6">
                <label 
                  for="inputCosto" 
                  class="form-label fw-bold mb-1"
                >
                  Costo:
                </label>
                <input 
                  type="number" 
                  class="form-control" 
                  id="inputCosto"
                  step="any"
                  v-model="form.costo"
                />
              </div>

              <div class="col-sm-6">
                <label 
                  for="inputPrecio" 
                  class="form-label fw-bold mb-1"
                >
                  Precio:
                </label>
                <input 
                  type="number" 
                  class="form-control" 
                  id="inputPrecio"
                  step="any"
                  v-model.number="form.precio"
                />
              </div>

              <div class="col-sm-12">
                <label 
                  for="inputDescripcion" 
                  class="form-label fw-bold mb-1"
                >
                  Descripción:
                </label>
                <textarea 
                  class="form-control" 
                  id="inputDescripcion" 
                  rows="6"
                  v-model="form.descripcion"
                ></textarea>
              </div>

              <div class="col-sm-12 mt-2" v-if="reg !== ''">
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

            <div class="col-sm-12 text-end mt-4">
              <button
                v-if="reg !== ''"
                type="button" 
                class="btn btn-light me-2"
                @click="$emit('regresar')"
              >
                Cancelar
              </button>

              <button type="submit" class="btn btn-primary" :disabled="btnGuardar">
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

        </div>
      </div>
    </div>
  </div>
</template>

<script>
  import Blitz from "@/mixins/Blitz.js"
  import Imagen from "@/components/general/Imagen.vue"

  export default {
    name: "FormProducto",
    mixins: [Blitz],
    props: {
      producto: {
        type: Object,
        default: null
      }
    },
    data: () => ({
      urlFoto: null,
      cat: {},
      inicio: false
    }),
    created() {
      this.formEspecial = true
      this.autoBuscar = false
      this._emit = true

      this.url = "producto/producto"

      if (this.producto != null) {
        this.setDataForm(this.producto)
      } else {
        this.form = {
          tipo: "B",
          activo: 1
        }  
      }
      
      this.getDatos()
    },
    methods: {
      getDatos() {
        this.$http
        .get(`${this.$baseUrl}/${this.url}/get_datos`)
        .then(res => {

          this.inicio = false
          this.cat = res.data.cat

        }).catch(e => {
          this.inicio = false
          console.log(e)
        })
      },
      subirImagen(f) {
        if (f.target.files[0]) {
        
          let tmp = f.target.files[0]
          this.urlFoto = tmp
          this.form.imagen = tmp
        }
      }
    },
    computed: {
      marcas() {
        if (this.cat.marcas) {
          return this.setDatoSelect(this.cat.marcas, "id", "nombre")
        } 

        return []
      },
      categorias() {
        if (this.cat.categorias) {
          return this.setDatoSelect(this.cat.categorias, "id", "nombre")
        } 

        return []
      },
      ums() {
        if (this.cat.ums) {
          return this.setDatoSelect(this.cat.ums, "id", "nombre")
        } 

        return []
      },
      imgActual() {
        return this.urlFoto || (this.producto?.imagen_key ? this.producto.imagen_key : null)
      }
    },
    components: {
      Imagen
    },
    watch: {
      producto(v) {
        this.setDataForm(v)
        this.urlFoto = null
      }
    }
  }
</script>

<style>
  #file-upload {
    display: none;
  }
</style>
